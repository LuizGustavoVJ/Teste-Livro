<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UploadService;
use App\Models\Arquivo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $uploadService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->uploadService = new UploadService();
    }

    /**
     * Testa se um arquivo válido pode ser enviado com sucesso.
     */
    public function test_pode_enviar_arquivo_valido_com_sucesso()
    {
        $file = UploadedFile::fake()->image('teste.jpg', 300, 400);

        $result = $this->uploadService->uploadArquivo($file);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('path', $result);
        $this->assertNotNull($result['id']);
        $this->assertNotNull($result['path']);

        // Verifica se o arquivo foi salvo no storage
        $this->assertTrue(Storage::disk('public')->exists($result['path']));

        // Verifica se foi criado no banco de dados
        $this->assertDatabaseHas('arquivos', [
            'id' => $result['id'],
            'nome_original' => 'teste.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Testa se arquivo muito grande é rejeitado.
     */
    public function test_rejeita_arquivo_muito_grande()
    {
        // Criar arquivo maior que 5MB
        $file = UploadedFile::fake()->create('grande.pdf', 6 * 1024 * 1024); // 6MB

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tamanho do arquivo maior que o permitido.');

        $this->uploadService->uploadArquivo($file);
    }

    /**
     * Testa se tipo de arquivo não permitido é rejeitado.
     */
    public function test_rejeita_tipo_arquivo_nao_permitido()
    {
        $file = UploadedFile::fake()->create('documento.txt', 1000);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tipo de arquivo não permitido.');

        $this->uploadService->uploadArquivo($file);
    }

    /**
     * Testa se arquivo PNG é aceito.
     */
    public function test_aceita_arquivo_png()
    {
        $file = UploadedFile::fake()->image('teste.png', 300, 400);

        $result = $this->uploadService->uploadArquivo($file);

        $this->assertIsArray($result);
        $this->assertEquals('image/png', Arquivo::find($result['id'])->mime_type);
    }

    /**
     * Testa se arquivo GIF é aceito.
     */
    public function test_aceita_arquivo_gif()
    {
        $file = UploadedFile::fake()->image('teste.gif', 300, 400);

        $result = $this->uploadService->uploadArquivo($file);

        $this->assertIsArray($result);
        $this->assertEquals('image/gif', Arquivo::find($result['id'])->mime_type);
    }

    /**
     * Testa se arquivo PDF é aceito.
     */
    public function test_aceita_arquivo_pdf()
    {
        $file = UploadedFile::fake()->create('documento.pdf', 1000, 'application/pdf');

        $result = $this->uploadService->uploadArquivo($file);

        $this->assertIsArray($result);
        $this->assertEquals('application/pdf', Arquivo::find($result['id'])->mime_type);
    }

    /**
     * Testa se o nome do arquivo é sanitizado.
     */
    public function test_nome_arquivo_e_sanitizado()
    {
        $file = UploadedFile::fake()->image('teste com espaços.jpg', 300, 400);

        $result = $this->uploadService->uploadArquivo($file);

        // Verifica se o caminho não contém espaços
        $this->assertStringNotContainsString(' ', $result['path']);
    }

    /**
     * Testa se arquivo pode ser deletado com sucesso.
     */
    public function test_pode_deletar_arquivo_com_sucesso()
    {
        $file = UploadedFile::fake()->image('teste.jpg', 300, 400);
        $result = $this->uploadService->uploadArquivo($file);

        $this->assertTrue(Storage::disk('public')->exists($result['path']));

        $deletado = $this->uploadService->deletarArquivo($result['path']);

        $this->assertTrue($deletado);
        $this->assertFalse(Storage::disk('public')->exists($result['path']));
    }

    /**
     * Testa se deletar arquivo inexistente retorna false.
     */
    public function test_deletar_arquivo_inexistente_retorna_false()
    {
        $deletado = $this->uploadService->deletarArquivo('caminho/inexistente.jpg');

        $this->assertFalse($deletado);
    }

    /**
     * Testa se deletar com caminho null retorna false.
     */
    public function test_deletar_com_caminho_null_retorna_false()
    {
        $deletado = $this->uploadService->deletarArquivo(null);

        $this->assertFalse($deletado);
    }

    /**
     * Testa se o arquivo é salvo na pasta correta.
     */
    public function test_arquivo_e_salvo_na_pasta_correta()
    {
        $file = UploadedFile::fake()->image('teste.jpg', 300, 400);

        $result = $this->uploadService->uploadArquivo($file);

        $this->assertStringStartsWith('uploads/imagens', $result['path']);
    }
}

