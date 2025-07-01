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
        $books = Book::with(['authors', 'subjects'])->paginate(10);
        $books->withPath(request()->url());
        return view('books.index', compact('books'));
    }

    /**
     * Mostra o formulário para criar um novo livro.
     */
    public function create()
    {
        $authors = Author::all();
        $subjects = Subject::all();
        return view('books.create', compact('authors', 'subjects'));
    }

    /**
     * Armazena um livro recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $validador = Validator::make(
            $request->all(),
            [
            'title' => 'required|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|max:13',
            'price' => 'required|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ]
        );

        if ($validador->fails()) {
            return redirect()->back()
                ->withErrors($validador)
                ->withInput();
        }

        $caminhoImagem = null;
        try {
            DB::beginTransaction();

            if ($request->hasFile("cover_image")) {
                $upload = $this->servicoUpload->uploadArquivo($request->file("cover_image"));
                $caminhoImagem = $upload['path'];
                if (!$caminhoImagem) {
                    throw new \Exception("Falha ao fazer upload da imagem de capa.");
                }
            }

            $book = Book::create(
                [
                "title" => $request->input('title'),
                "publication_year" => $request->input('publication_year'),
                "isbn" => $request->input('isbn'),
                "price" => $request->input('price'),
                "cover_image_path" => $caminhoImagem,
                ]
            );

            $book->authors()->attach($request->input('authors'));
            $book->subjects()->attach($request->input('subjects'));

            DB::commit();

            return redirect()->route("books.index")
                ->with("success", "Livro criado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($caminhoImagem) {
                $this->servicoUpload->deletarArquivo($caminhoImagem);
            }
            return redirect()->back()
                ->with("error", "Erro ao criar livro: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe o livro especificado.
     */
    public function show(Book $book)
    {
        $book->load(["authors", "subjects"]);
        return view("books.show", compact("book"));
    }

    /**
     * Mostra o formulário para editar o livro especificado.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $subjects = Subject::all();
        $book->load(["authors", "subjects"]);
        return view("books.edit", compact("book", "authors", "subjects"));
    }

    /**
     * Atualiza o livro especificado no armazenamento.
     */
    public function update(Request $request, Book $book)
    {
        $validador = Validator::make(
            $request->all(),
            [
            'title' => 'required|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|max:13',
            'price' => 'required|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            "cover_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            ]
        );

        if ($validador->fails()) {
            return redirect()->back()
                ->withErrors($validador)
                ->withInput();
        }

        $caminhoImagem = $book->cover_image_path;
        try {
            DB::beginTransaction();

            if ($request->hasFile("cover_image")) {
                if ($caminhoImagem) {
                    $this->servicoUpload->deletarArquivo($caminhoImagem);
                }
                $caminhoImagem = $this->servicoUpload->uploadArquivo($request->file("cover_image"), "capas_livros");
                $caminhoImagem = $caminhoImagem['path'];
                if (!$caminhoImagem) {
                    throw new \Exception("Falha ao fazer upload da nova imagem de capa.");
                }
            }

            $book->update(
                [
                "title" => $request->input('title'),
                "publication_year" => $request->input('publication_year'),
                "isbn" => $request->input('isbn'),
                "price" => $request->input('price'),
                "cover_image_path" => $caminhoImagem,
                ]
            );

            $book->authors()->sync($request->input('authors'));
            $book->subjects()->sync($request->input('subjects'));

            DB::commit();

            return redirect()->route("books.index")
                ->with("success", "Livro atualizado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with("error", "Erro ao atualizar livro: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove o livro especificado do armazenamento.
     */
    public function destroy(Book $book)
    {
        try {
            if ($book->cover_image_path) {
                $this->servicoUpload->deletarArquivo($book->cover_image_path);
            }
            $book->delete();
            return redirect()->route("books.index")
                ->with("success", "Livro excluído com sucesso!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with("error", "Erro ao excluir livro: " . $e->getMessage());
        }
    }
}
