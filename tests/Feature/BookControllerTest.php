<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function autenticarAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        return $admin;
    }

    public function test_lista_livros()
    {
        $this->autenticarAdmin();
        Book::factory()->count(2)->create();
        $resposta = $this->get(route('books.index'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('books.list');
        $resposta->assertViewHas('books');
    }

    public function test_exibe_formulario_de_cadastro_de_livro()
    {
        $this->autenticarAdmin();
        $resposta = $this->get(route('books.create'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('books.create');
    }

    public function test_cadastra_novo_livro()
    {
        $this->autenticarAdmin();
        $dados = [
            'title' => 'Livro Teste',
            'author' => 'Autor Teste',
            'description' => 'Descrição do livro',
            'status' => 1,
        ];
        $resposta = $this->post(route('books.store.post'), $dados);
        $resposta->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['title' => 'Livro Teste']);
    }

    public function test_exibe_formulario_de_edicao_de_livro()
    {
        $this->autenticarAdmin();
        $livro = Book::factory()->create();
        $resposta = $this->get(route('books.edit', $livro->id));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('books.edit');
        $resposta->assertViewHas('book', $livro);
    }

    public function test_atualiza_livro()
    {
        $this->autenticarAdmin();
        $livro = Book::factory()->create();
        $dados = [
            'title' => 'Livro Atualizado',
            'author' => 'Autor Atualizado',
            'description' => 'Nova descrição',
            'status' => 1,
        ];
        $resposta = $this->post(route('books.update.post', $livro->id), $dados);
        $resposta->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['title' => 'Livro Atualizado']);
    }

    public function test_remove_livro()
    {
        $this->autenticarAdmin();
        $livro = Book::factory()->create();
        $resposta = $this->delete(route('books.destroy', $livro->id), ['id' => $livro->id]);
        $resposta->assertJson(['status' => true]);
        $this->assertSoftDeleted('books', ['id' => $livro->id]);
    }
}