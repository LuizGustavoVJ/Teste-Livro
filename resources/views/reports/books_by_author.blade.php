@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Relatório de Livros por Autor</h2>
                </div>
                <div class="card-body">
                    @foreach($authors as $author)
                        <h3>{{ $author->name }}</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Ano de Publicação</th>
                                    <th>ISBN</th>
                                    <th>Valor (R$)</th>
                                    <th>Assuntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($author->books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->publication_year }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ number_format($book->price, 2, ',', '.') }}</td>
                                        <td>
                                            @foreach($book->subjects as $subject)
                                                {{ $subject->description }}@if(!$loop->last), @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

