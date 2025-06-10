<x-mail::message>
# Bem-vindo(a) ao Sistema de Livros, {{ $nomeUsuario }}!

Obrigado por se juntar à nossa comunidade. Estamos muito felizes em tê-lo(a) a bordo!

Nosso sistema permite que você gerencie sua biblioteca digital de forma fácil e intuitiva. Você pode:

*   Cadastrar e organizar seus livros.
*   Gerenciar autores e assuntos.
*   Gerar relatórios detalhados.

Para começar a explorar, clique no botão abaixo:

<x-mail::button :url="route("login")">
Acessar o Sistema
</x-mail::button>

Se tiver alguma dúvida ou precisar de ajuda, não hesite em nos contatar.

Atenciosamente,
Equipe Sistema de Livros
</x-mail::message>


