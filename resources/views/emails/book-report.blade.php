<x-mail::message>
# Relatório de Livros

Olá!

Este é o seu relatório diário/semanal/mensal (ajustar conforme a frequência) sobre os livros cadastrados no sistema.

**Total de Livros Cadastrados:** {{ $totalLivros }}

## Livros por Autor:

<x-mail::table>
| Autor | Total de Livros |
|:------|:----------------|
@foreach($livrosPorAutor as $item)
| {{ $item->autor }} | {{ $item->total_livros }} |
@endforeach
</x-mail::table>

Atenciosamente,
Equipe Sistema de Livros
</x-mail::message>


