<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Livros por Autor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório de Livros por Autor</h1>
    
    @foreach($authors as $author)
        <h2>{{ $author->name }}</h2>
        <table>
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
</body>
</html>

