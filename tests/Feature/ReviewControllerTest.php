<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function autenticarUsuario()
    {
        $usuario = User::factory()->create();
        $this->actingAs($usuario);
        return $usuario;
    }

    public function test_admin_lista_avaliacoes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        Review::factory()->count(2)->create();
        $resposta = $this->get(route('account.reviews'));
        $resposta->assertStatus(200);
        $resposta->assertViewIs('account.reviews.list');
        $resposta->assertViewHas('reviews');
    }

    public function test_usuario_pode_criar_uma_avaliacao()
    {
        $usuario = $this->autenticarUsuario();
        $livro = Book::factory()->create();

        $dados = [
            'book_id' => $livro->id,
            'review' => 'Ótimo livro!',
            'rating' => 5,
        ];

        $resposta = $this->postJson(route('books.review.post'), $dados);
        $resposta->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'book_id' => $livro->id,
            'user_id' => $usuario->id,
            'review' => 'Ótimo livro!',
            'rating' => 5,
        ]);
    }

    public function test_usuario_nao_pode_avaliar_mesmo_livro_duas_vezes()
    {
        $usuario = $this->autenticarUsuario();
        $livro = Book::factory()->create();
        Review::factory()->create([
            'book_id' => $livro->id,
            'user_id' => $usuario->id,
        ]);

        $dados = [
            'book_id' => $livro->id,
            'review' => 'Tentando avaliar de novo',
            'rating' => 4,
        ];

        $resposta = $this->postJson(route('books.review.post'), $dados);
        $resposta->assertJson(['success' => false]);
    }

    public function test_usuario_pode_editar_sua_avaliacao()
    {
        $usuario = $this->autenticarUsuario();
        $review = Review::factory()->create(['user_id' => $usuario->id]);

        $dados = [
            'review' => 'Editado pelo usuário',
            'status' => 1,
            'rating' => 5,
        ];

        $resposta = $this->post(route('account.myReviews.update', $review->id), $dados);
        $resposta->assertRedirect(route('account.myReviews'));
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'review' => 'Editado pelo usuário',
        ]);
    }

    public function test_usuario_pode_excluir_sua_avaliacao()
    {
        $usuario = $this->autenticarUsuario();
        $review = Review::factory()->create(['user_id' => $usuario->id]);

        $resposta = $this->deleteJson(route('account.reviews.deleteReview'), ['id' => $review->id]);
        $resposta->assertJson(['success' => true]);
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);
    }
}