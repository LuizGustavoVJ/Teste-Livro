<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Arquivo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArquivoTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se um arquivo pode ser criado com sucesso.
     */
    public function test_arquivo_pode_ser_criado_com_sucesso()
    {
        $dadosArquivo = [
            'nome_original' => 'teste.jpg',
            'caminho' => 'uploads/imagens/teste_1234567890.jpg',
            'mime_type' => 'image/jpeg',
        ];

        $arquivo = Arquivo::create($dadosArquivo);

        $this->assertInstanceOf(Arquivo::class, $arquivo);
        $this->assertEquals('teste.jpg', $arquivo->nome_original);
        $this->assertEquals('uploads/imagens/teste_1234567890.jpg', $arquivo->caminho);
        $this->assertEquals('image/jpeg', $arquivo->mime_type);
        $this->assertDatabaseHas('arquivos', $dadosArquivo);
    }

    /**
     * Testa se o nome_original é obrigatório.
     */
    public function test_nome_original_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Arquivo::create([
            'caminho' => 'uploads/imagens/teste.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Testa se o caminho é obrigatório.
     */
    public function test_caminho_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Arquivo::create([
            'nome_original' => 'teste.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Testa se o mime_type é obrigatório.
     */
    public function test_mime_type_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Arquivo::create([
            'nome_original' => 'teste.jpg',
            'caminho' => 'uploads/imagens/teste.jpg',
        ]);
    }

    /**
     * Testa se o relacionamento com User funciona corretamente.
     */
    public function test_relacionamento_com_user_funciona()
    {
        $arquivo = Arquivo::factory()->create();
        $user = User::factory()->create(['arquivo_id' => $arquivo->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $arquivo->user);
        $this->assertTrue($arquivo->user->contains($user));
    }

    /**
     * Testa se um arquivo pode ser atualizado.
     */
    public function test_arquivo_pode_ser_atualizado()
    {
        $arquivo = Arquivo::factory()->create(['nome_original' => 'original.jpg']);

        $arquivo->update(['nome_original' => 'atualizado.jpg']);

        $this->assertEquals('atualizado.jpg', $arquivo->fresh()->nome_original);
        $this->assertDatabaseHas('arquivos', [
            'id' => $arquivo->id,
            'nome_original' => 'atualizado.jpg',
        ]);
    }

    /**
     * Testa se um arquivo pode ser excluído.
     */
    public function test_arquivo_pode_ser_excluido()
    {
        $arquivo = Arquivo::factory()->create();
        $arquivoId = $arquivo->id;

        $arquivo->delete();

        $this->assertDatabaseMissing('arquivos', ['id' => $arquivoId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_arquivo_possui_timestamps()
    {
        $arquivo = Arquivo::factory()->create();

        $this->assertNotNull($arquivo->created_at);
        $this->assertNotNull($arquivo->updated_at);
    }

    /**
     * Testa se um arquivo pode ter diferentes tipos MIME.
     */
    public function test_arquivo_pode_ter_diferentes_tipos_mime()
    {
        $tiposMime = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
        ];

        foreach ($tiposMime as $mimeType) {
            $arquivo = Arquivo::factory()->create(['mime_type' => $mimeType]);
            $this->assertEquals($mimeType, $arquivo->mime_type);
        }
    }

    /**
     * Testa se o caminho pode ter diferentes formatos.
     */
    public function test_caminho_pode_ter_diferentes_formatos()
    {
        $caminhos = [
            'uploads/imagens/teste.jpg',
            'uploads/documentos/relatorio.pdf',
            'uploads/imagens/subpasta/imagem.png',
        ];

        foreach ($caminhos as $caminho) {
            $arquivo = Arquivo::factory()->create(['caminho' => $caminho]);
            $this->assertEquals($caminho, $arquivo->caminho);
        }
    }
}

