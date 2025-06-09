<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $books = Book::with(['authors', 'subjects'])->get();
            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar livros',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
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
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

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

            return response()->json([
                'success' => true,
                'message' => 'Livro criado com sucesso',
                'data' => $book->load(['authors', 'subjects'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $book = Book::with(['authors', 'subjects'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $book
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
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
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $book = Book::findOrFail($id);
            $book->update([
                'title' => $request->title,
                'publication_year' => $request->publication_year,
                'isbn' => $request->isbn,
                'price' => $request->price,
            ]);

            $book->authors()->sync($request->authors);
            $book->subjects()->sync($request->subjects);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Livro atualizado com sucesso',
                'data' => $book->load(['authors', 'subjects'])
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livro excluído com sucesso'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

