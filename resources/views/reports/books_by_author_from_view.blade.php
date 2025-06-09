@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Relatório de Livros por Autor (da View do Banco)</h2>
                </div>
                <div class="card-body">
                    @php
                        $currentAuthor = null;
                    @endphp
                    
                    @foreach($data as $row)
                        @if($currentAuthor != $row->author_id)
                            @if($currentAuthor !== null)
                                </tbody>
                                </table>
                            @endif
                            
                            <h3>{{ $row->author_name }}</h3>
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
                            
                            @php
                                $currentAuthor = $row->author_id;
                            @endphp
                        @endif
                        
                        <tr>
                            <td>{{ $row->book_title }}</td>
                            <td>{{ $row->publication_year }}</td>
                            <td>{{ $row->isbn }}</td>
                            <td>{{ number_format($row->price, 2, ',', '.') }}</td>
                            <td>{{ $row->subjects }}</td>
                        </tr>
                    @endforeach
                    
                    @if(count($data) > 0)
                        </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

