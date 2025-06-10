<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Realiza o upload de um arquivo para o disco público.
     *
     * @param UploadedFile $arquivo
     * @param string $pasta
     * @return string|null O caminho do arquivo salvo ou null em caso de falha.
     */
    public function uploadArquivo(UploadedFile $arquivo, string $pasta = "uploads")
    {
        try {
            // Validação básica de tamanho e tipo de arquivo
            $maxSize = 5 * 1024 * 1024; // 5MB
            $allowedMimeTypes = [
                "image/jpeg", "image/png", "image/jpg", "image/gif", "image/svg",
            ];

            if ($arquivo->getSize() > $maxSize) {
                Log::error("Erro de upload: Tamanho do arquivo excedido.", ["tamanho" => $arquivo->getSize()]);
                return null;
            }

            if (!in_array($arquivo->getMimeType(), $allowedMimeTypes)) {
                Log::error("Erro de upload: Tipo de arquivo não permitido.", ["mime_type" => $arquivo->getMimeType()]);
                return null;
            }

            // Salva o arquivo no disco público
            $caminho = $arquivo->store($pasta, "public");

            Log::info("Upload de arquivo concluído com sucesso.", ["caminho" => $caminho]);
            return $caminho;
        } catch (\Exception $e) {
            Log::error("Erro ao realizar upload de arquivo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Deleta um arquivo do disco público.
     *
     * @param string|null $caminhoArquivo O caminho do arquivo a ser deletado.
     * @return bool True se o arquivo foi deletado com sucesso, false caso contrário.
     */
    public function deletarArquivo(?string $caminhoArquivo)
    {
        if ($caminhoArquivo && Storage::disk("public")->exists($caminhoArquivo)) {
            try {
                Storage::disk("public")->delete($caminhoArquivo);
                Log::info("Arquivo deletado com sucesso.", ["caminho" => $caminhoArquivo]);
                return true;
            } catch (\Exception $e) {
                Log::error("Erro ao deletar arquivo: " . $e->getMessage(), ["caminho" => $caminhoArquivo]);
                return false;
            }
        }
        return false;
    }
}


