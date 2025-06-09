<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Livros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .author-section {
            margin-bottom: 30px;
        }
        .author-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .book-item {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            border-left: 4px solid #007bff;
        }
        .book-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .book-details {
            font-size: 14px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Relatório de Livros por Autor</h1>
            <p>Sistema de Gerenciamento de Livros</p>
        </div>
        
        <div class="content">
            @if(isset($reportData['authors']) && count($reportData['authors']) > 0)
                @foreach($reportData['authors'] as $author)
                    <div class="author-section">
                        <div class="author-name">{{ $author['name'] }}</div>
                        
                        @if(isset($author['books']) && count($author['books']) > 0)
                            @foreach($author['books'] as $book)
                                <div class="book-item">
                                    <div class="book-title">{{ $book['title'] }}</div>
                                    <div class="book-details">
                                        <strong>Ano:</strong> {{ $book['publication_year'] ?? 'N/A' }} |
                                        <strong>ISBN:</strong> {{ $book['isbn'] ?? 'N/A' }} |
                                        <strong>Valor:</strong> R$ {{ number_format($book['price'], 2, ',', '.') }}
                                        @if(isset($book['subjects']) && count($book['subjects']) > 0)
                                            <br><strong>Assuntos:</strong> 
                                            @foreach($book['subjects'] as $subject)
                                                {{ $subject['description'] }}@if(!$loop->last), @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p><em>Nenhum livro encontrado para este autor.</em></p>
                        @endif
                    </div>
                @endforeach
            @else
                <p>Nenhum dado encontrado para o relatório.</p>
            @endif
        </div>
        
        <div class="footer">
            <p>Este é um e-mail automático gerado pelo Sistema de Gerenciamento de Livros.</p>
            <p>Data de geração: {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>

