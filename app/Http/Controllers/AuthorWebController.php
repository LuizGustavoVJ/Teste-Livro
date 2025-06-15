<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::with('books')->paginate(10);
        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255|unique:authors,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Author::create($request->all());
            return redirect()->route('authors.index')
                ->with('success', 'Autor criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar autor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author->load('books');
        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $author->update($request->all());
            return redirect()->route('authors.index')
                ->with('success', 'Autor atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar autor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        try {
            $author->delete();
            return redirect()->route('authors.index')
                ->with('success', 'Autor excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir autor: ' . $e->getMessage());
        }
    }
}

