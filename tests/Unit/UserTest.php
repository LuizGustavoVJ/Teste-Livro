<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Arquivo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se um usuário pode ser criado com sucesso.
     */
    public function test_usuario_pode_ser_criado_com_sucesso()
    {
        $dadosUsuario = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($dadosUsuario);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('João Silva', $user->name);
        $this->assertEquals('joao@example.com', $user->email);
        $this->assertDatabaseHas('users', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ]);
    }

    /**
     * Testa se o nome do usuário é obrigatório.
     */
    public function test_nome_usuario_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'email' => 'teste@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Testa se o email do usuário é obrigatório.
     */
    public function test_email_usuario_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Teste',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Testa se o relacionamento com Arquivo funciona corretamente.
     */
    public function test_relacionamento_com_arquivo_funciona()
    {
        $arquivo = Arquivo::factory()->create();
        $user = User::factory()->create(['arquivo_id' => $arquivo->id]);

        $this->assertInstanceOf(Arquivo::class, $user->arquivo);
        $this->assertEquals($arquivo->id, $user->arquivo->id);
    }

    /**
     * Testa se um usuário pode não ter arquivo (arquivo_id null).
     */
    public function test_usuario_pode_nao_ter_arquivo()
    {
        $user = User::factory()->create(['arquivo_id' => null]);

        $this->assertNull($user->arquivo);
    }

    /**
     * Testa se um usuário pode ser atualizado.
     */
    public function test_usuario_pode_ser_atualizado()
    {
        $user = User::factory()->create(['name' => 'Nome Original']);

        $user->update(['name' => 'Nome Atualizado']);

        $this->assertEquals('Nome Atualizado', $user->fresh()->name);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    /**
     * Testa se um usuário pode ser excluído.
     */
    public function test_usuario_pode_ser_excluido()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_usuario_possui_timestamps()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    /**
     * Testa se a senha é hasheada automaticamente.
     */
    public function test_senha_e_hasheada_automaticamente()
    {
        $senha = 'password123';
        $user = User::factory()->create(['password' => $senha]);

        $this->assertNotEquals($senha, $user->password);
        $this->assertTrue(Hash::check($senha, $user->password));
    }

    /**
     * Testa se o email é único.
     */
    public function test_email_deve_ser_unico()
    {
        User::factory()->create(['email' => 'teste@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create(['email' => 'teste@example.com']);
    }

    /**
     * Testa se o email_verified_at pode ser null.
     */
    public function test_email_verified_at_pode_ser_null()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->assertNull($user->email_verified_at);
    }

    /**
     * Testa se o remember_token pode ser null.
     */
    public function test_remember_token_pode_ser_null()
    {
        $user = User::factory()->create(['remember_token' => null]);

        $this->assertNull($user->remember_token);
    }
}

