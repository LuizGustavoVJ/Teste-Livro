<?php

namespace App\Services;

use App\Models\Arquivo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Realiza o upload do arquivo (imagem ou documento) e registra no banco.
     *
     * @param UploadedFile $file
     * @return array
     * @throws \Exception
     */
    public function uploadArquivo(UploadedFile $file)
    {
        // Tamanho máximo permitido: 5MB
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file->getSize() > $maxSize) {
            Log::error('Tamanho do arquivo excedido: ' . $file->getSize());
            Session::flash('erro_upload', 'Arquivo muito grande (máx 5MB).');
            throw new \Exception('Tamanho do arquivo maior que o permitido.');
        }

        // Tipos permitidos
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/svg+xml',
            'application/pdf', 'application/vnd.ms-excel', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text',
        ];

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            Log::error('Tipo de arquivo não permitido: ' . $file->getMimeType());
            Session::flash('erro_upload', 'Tipo de arquivo não permitido.');
            throw new \Exception('Tipo de arquivo não permitido.');
        }

        // Pasta baseada no tipo
        $folder = str_starts_with($file->getMimeType(), 'image/')
            ? 'uploads/imagens'
            : 'uploads/documentos';

        // Nome seguro e único para o arquivo
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = preg_replace("/[^a-zA-Z0-9]/", "_", $filename);
        $filename .= '_' . time() . '.' . $file->getClientOriginalExtension();

        // Upload
        $path = $file->storeAs($folder, $filename, 'public');

        // Salva no banco de dados com os campos da nova model
        $arquivo = Arquivo::create([
            'nome_original' => $file->getClientOriginalName(),
            'caminho' => $path,
            'mime_type' => $file->getMimeType(),
        ]);

        return [
            'id' => $arquivo->id,
            'url' => asset('storage/' . $path),
            'path' => $path,
        ];
    }
}
