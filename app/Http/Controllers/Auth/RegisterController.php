<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\BoasVindasMail;
use App\Services\UploadService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Events\UserRegistered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "";

    protected $uploadService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UploadService $uploadService)
    {
        $this->middleware("guest");
        $this->uploadService = $uploadService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "string", "min:8", "confirmed"],
            "imagem" => ["nullable", "image", "mimes:jpeg,png,jpg,gif,bmp", "max:5120"], // 5MB
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        Log::info("Iniciando criação de usuário", ["data" => $data]);

        $upload = null;

        if (request()->hasFile("imagem")) {
            Log::info("Arquivo de imagem recebido", [
                "mime_type" => request()->file("imagem")->getMimeType(),
                "size" => request()->file("imagem")->getSize(),
                "original_name" => request()->file("imagem")->getClientOriginalName(),
            ]);

            try {
                $upload = $this->uploadService->uploadArquivo(request()->file("imagem"));
                Log::info("Upload concluído com sucesso", ["upload" => $upload]);
            } catch (\Exception $e) {
                Log::error("Erro durante upload da imagem", ["erro" => $e->getMessage()]);
            }
        } else {
            Log::warning("Nenhum arquivo de imagem enviado na requisição.");
        }

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "arquivo_id" => $upload["id"] ?? null,
        ]);

        Log::info("Usuário criado com sucesso", ["user_id" => $user->id, "arquivo_id" => $user->arquivo_id]);

        // Dispara o evento UserRegistered
        event(new UserRegistered($user, ['origem' => 'registro']));

        return $user->load("arquivo");
    }
}


