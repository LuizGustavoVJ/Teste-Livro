<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use App\Models\Author;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * Envia o relatório de livros por autor por e-mail.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBookReport(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'email' => 'required|email',
            'subject' => 'string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
                ],
                422
            );
        }

        try {
            // Buscar dados do relatório
            $authors = Author::with('books.subjects')->get();

            // Preparar dados para o e-mail
            $reportData = [
                'authors' => $authors->map(
                    function ($author) {
                        return [
                        'name' => $author->name,
                        'books' => $author->books->map(
                            function ($book) {
                                return [
                                'title' => $book->title,
                                'publication_year' => $book->publication_year,
                                'isbn' => $book->isbn,
                                'price' => $book->price,
                                'subjects' => $book->subjects->map(
                                    function ($subject) {
                                        return [
                                        'description' => $subject->description
                                        ];
                                    }
                                )->toArray()
                                ];
                            }
                        )->toArray()
                        ];
                    }
                )->toArray()
            ];

            $subject = $request->input('subject', 'Relatório de Livros por Autor');

            // Despachar o job para a fila
            SendEmailJob::dispatch($request->email, $subject, $reportData);

            return response()->json(
                [
                'success' => true,
                'message' => 'E-mail adicionado à fila de envio com sucesso'
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Erro ao processar solicitação de e-mail',
                'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Exibe o formulário para envio de e-mail.
     *
     * @return \Illuminate\View\View
     */
    public function showEmailForm()
    {
        return view('emails.send_form');
    }
}
