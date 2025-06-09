<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['authors', 'subjects'])->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        $subjects = Subject::all();
        return view('books.create', compact('authors', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|max:13',
            'price' => 'required|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $book = Book::create([
                'title' => $request->title,
                'publication_year' => $request->publication_year,
                'isbn' => $request->isbn,
                'price' => $request->price,
            ]);

            $book->authors()->attach($request->authors);
            $book->subjects()->attach($request->subjects);

            DB::commit();

            return redirect()->route('books.index')
                ->with('success', 'Livro criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erro ao criar livro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['authors', 'subjects']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $subjects = Subject::all();
        $book->load(['authors', 'subjects']);
        return view('books.edit', compact('book', 'authors', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|max:13',
            'price' => 'required|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $book->update([
                'title' => $request->title,
                'publication_year' => $request->publication_year,
                'isbn' => $request->isbn,
                'price' => $request->price,
            ]);

            $book->authors()->sync($request->authors);
            $book->subjects()->sync($request->subjects);

            DB::commit();

            return redirect()->route('books.index')
                ->with('success', 'Livro atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erro ao atualizar livro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete();
            return redirect()->route('books.index')
                ->with('success', 'Livro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir livro: ' . $e->getMessage());
        }
    }
}

