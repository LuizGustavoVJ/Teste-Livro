<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\UploadService;

class BookWebController extends Controller
{
    protected $servicoUpload;

    public function __construct(UploadService $servicoUpload)
    {
        $this->servicoUpload = $servicoUpload;
    }

    /**
     * Exibe uma listagem dos livros.
     */
    public function index()
    {
        $livros = Book::with(["authors", "subjects"])->paginate(10);
        return view("books.index", compact("livros"));
    }

    /**
     * Mostra o formulário para criar um novo livro.
     */
    public function create()
    {
        $autores = Author::all();
        $assuntos = Subject::all();
        return view("books.create", compact("autores", "assuntos"));
    }

    /**
     * Armazena um livro recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
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
                $caminhoImagem = $this->servicoUpload->uploadArquivo($request->file("imagem_capa"), "capas_livros");
                if (!$caminhoImagem) {
                    throw new \Exception("Falha ao fazer upload da imagem de capa.");
                }
            }

            $livro = Book::create([
                "title" => $request->titulo,
                "publication_year" => $request->ano_publicacao,
                "isbn" => $request->isbn,
                "price" => $request->preco,
                "cover_image_path" => $caminhoImagem,
            ]);

            $livro->authors()->attach($request->autores);
            $livro->subjects()->attach($request->assuntos);

            DB::commit();

            return redirect()->route("books.index")
                ->with("sucesso", "Livro criado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($caminhoImagem) {
                $this->servicoUpload->deletarArquivo($caminhoImagem);
            }
            return redirect()->back()
                ->with("erro", "Erro ao criar livro: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe o livro especificado.
     */
    public function show(Book $livro)
    {
        $livro->load(["authors", "subjects"]);
        return view("books.show", compact("livro"));
    }

    /**
     * Mostra o formulário para editar o livro especificado.
     */
    public function edit(Book $livro)
    {
        $autores = Author::all();
        $assuntos = Subject::all();
        $livro->load(["authors", "subjects"]);
        return view("books.edit", compact("livro", "autores", "assuntos"));
    }

    /**
     * Atualiza o livro especificado no armazenamento.
     */
    public function update(Request $request, Book $livro)
    {
        $validador = Validator::make($request->all(), [
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

            $caminhoImagem = $livro->cover_image_path;
            if ($request->hasFile("imagem_capa")) {
                if ($caminhoImagem) {
                    $this->servicoUpload->deletarArquivo($caminhoImagem);
                }
                $caminhoImagem = $this->servicoUpload->uploadArquivo($request->file("imagem_capa"), "capas_livros");
                if (!$caminhoImagem) {
                    throw new \Exception("Falha ao fazer upload da nova imagem de capa.");
                }
            }

            $livro->update([
                "title" => $request->titulo,
                "publication_year" => $request->ano_publicacao,
                "isbn" => $request->isbn,
                "price" => $request->preco,
                "cover_image_path" => $caminhoImagem,
            ]);

            $livro->authors()->sync($request->autores);
            $livro->subjects()->sync($request->assuntos);

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
    public function destroy(Book $livro)
    {
        try {
            if ($livro->cover_image_path) {
                $this->servicoUpload->deletarArquivo($livro->cover_image_path);
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


