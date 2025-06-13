<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_exibe_formulario_de_registro()
    {
        $resposta = $this->get(route('account.register'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('account.register');
    }

    public function test_processa_registro_cria_usuario_e_redireciona()
    {
        $resposta = $this->post(route('account.processRegister.post'), [
            'name' => 'Usuário Teste',
            'email' => 'teste@example.com',
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
        ]);
        $resposta->assertRedirect(route('account.login'));
        $this->assertDatabaseHas('users', ['email' => 'teste@example.com']);
    }

    public function test_exibe_formulario_de_login()
    {
        $resposta = $this->get(route('account.login'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('account.login');
    }

    public function test_processa_login_autentica_usuario()
    {
        $usuario = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('senha12345'),
        ]);
        $resposta = $this->post(route('account.processLogin.post'), [
            'email' => 'login@example.com',
            'password' => 'senha12345',
        ]);
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_exibe_perfil_do_usuario()
    {
        $usuario = User::factory()->create();
        $resposta = $this->actingAs($usuario)->get(route('account.profile'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('account.profile');
        $resposta->assertViewHas('user', $usuario);
    }

    public function test_logout_desloga_usuario()
    {
        $usuario = User::factory()->create();
        $this->actingAs($usuario);
        $resposta = $this->get(route('account.logout'));
        $this->assertGuest();
        $resposta->assertRedirect(route('account.login'));
    }

    public function test_troca_de_senha_altera_senha_do_usuario()
    {
        $usuario = User::factory()->create([
            'password' => Hash::make('senhaantiga'),
        ]);
        $this->actingAs($usuario);
        $resposta = $this->post(route('account.changePassword.post'), [
            'old_password' => 'senhaantiga',
            'new_password' => 'novasenha123',
            'new_password_confirmation' => 'novasenha123',
        ]);
        $resposta->assertRedirect(route('account.profile'));
        $usuario->refresh();
        $this->assertTrue(Hash::check('novasenha123', $usuario->password));
    }
}