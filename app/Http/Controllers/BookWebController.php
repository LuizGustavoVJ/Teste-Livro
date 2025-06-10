<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookWebController extends Controller
{
    /**
     * Exibe uma listagem dos livros.
     */
    public function index()
    {
        $livros = Livro::with(["autores", "assuntos"])->paginate(10);
        return view("books.index", compact("livros"));
    }

    /**
     * Mostra o formulário para criar um novo livro.
     */
    public function create()
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();
        return view("books.create", compact("autores", "assuntos"));
    }

    /**
     * Armazena um livro recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $validador = Validador::make($request->all(), [
            "titulo" => "required|string|max:255",
            "ano_publicacao" => "nullable|integer",
            "isbn" => "nullable|string|max:13",
            "preco" => "required|numeric|min:0",
            "autores" => "required|array|min:1",
            "autores.*" => "exists:authors,id",
            "assuntos" => "required|array|min:1",
            "assuntos.*" => "exists:subjects,id",
            "imagem_capa" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        if ($validador->fails()) {
            return redirect()->back()
                ->withErrors($validador)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $caminhoImagem = null;
            if ($request->hasFile("imagem_capa")) {
                $caminhoImagem = $request->file("imagem_capa")->store("capas_livros", "public");
            }

            $livro = Livro::create([
                "titulo" => $request->titulo,
                "ano_publicacao" => $request->ano_publicacao,
                "isbn" => $request->isbn,
                "preco" => $request->preco,
                "caminho_imagem_capa" => $caminhoImagem,
            ]);

            $livro->autores()->attach($request->autores);
            $livro->assuntos()->attach($request->assuntos);

            DB::commit();

            return redirect()->route("books.index")
                ->with("sucesso", "Livro criado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($caminhoImagem) {
                Storage::disk("public")->delete($caminhoImagem);
            }
            return redirect()->back()
                ->with("erro", "Erro ao criar livro: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe o livro especificado.
     */
    public function show(Livro $livro)
    {
        $livro->load(["autores", "assuntos"]);
        return view("books.show", compact("livro"));
    }

    /**
     * Mostra o formulário para editar o livro especificado.
     */
    public function edit(Livro $livro)
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();
        $livro->load(["autores", "assuntos"]);
        return view("books.edit", compact("livro", "autores", "assuntos"));
    }

    /**
     * Atualiza o livro especificado no armazenamento.
     */
    public function update(Request $request, Livro $livro)
    {
        $validador = Validador::make($request->all(), [
            "titulo" => "required|string|max:255",
            "ano_publicacao" => "nullable|integer",
            "isbn" => "nullable|string|max:13",
            "preco" => "required|numeric|min:0",
            "autores" => "required|array|min:1",
            "autores.*" => "exists:authors,id",
            "assuntos" => "required|array|min:1",
            "assuntos.*" => "exists:subjects,id",
            "imagem_capa" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        if ($validador->fails()) {
            return redirect()->back()
                ->withErrors($validador)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $caminhoImagem = $livro->caminho_imagem_capa;
            if ($request->hasFile("imagem_capa")) {
                if ($caminhoImagem) {
                    Storage::disk("public")->delete($caminhoImagem);
                }
                $caminhoImagem = $request->file("imagem_capa")->store("capas_livros", "public");
            }

            $livro->update([
                "titulo" => $request->titulo,
                "ano_publicacao" => $request->ano_publicacao,
                "isbn" => $request->isbn,
                "preco" => $request->preco,
                "caminho_imagem_capa" => $caminhoImagem,
            ]);

            $livro->autores()->sync($request->autores);
            $livro->assuntos()->sync($request->assuntos);

            DB::commit();

            return redirect()->route("books.index")
                ->with("sucesso", "Livro atualizado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with("erro", "Erro ao atualizar livro: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove o livro especificado do armazenamento.
     */
    public function destroy(Livro $livro)
    {
        try {
            if ($livro->caminho_imagem_capa) {
                Storage::disk("public")->delete($livro->caminho_imagem_capa);
            }
            $livro->delete();
            return redirect()->route("books.index")
                ->with("sucesso", "Livro excluído com sucesso!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with("erro", "Erro ao excluir livro: " . $e->getMessage());
        }
    }
}


