<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    // Lista todas as avaliações (admin)
    public function index(Request $request){
        if ($request->has('success')) {
            session()->flash('success', $request->get('success'));
        }

        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $reviews = $reviews->whereHas('book', function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            });
        }
        
        $reviews = $reviews->paginate(10);
        
        return view('account.reviews.list', [
            'reviews' => $reviews,
        ]);
    }

    // Salva uma nova avaliação para um livro
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para avaliar.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'review' => 'required|min:5|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ]);
        }

        // Verifica se o usuário já avaliou este livro
        $countReviews = Review::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->count();
        if ($countReviews > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Você já avaliou este livro.',
                'redirect' => route('book.detail', $request->book_id) . '?error_review=' . urlencode('Você já avaliou este livro.'),
                'errors' => ['review' => ['Você já avaliou este livro.']],
            ]);
        }

        // Cria a avaliação
        Review::create([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'review' => $request->review,
            'rating' => $request->rating,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Avaliação salva com sucesso!',
            'redirect' => route('book.detail', $request->book_id)
        ]);
    }

    // Exibe o formulário de edição de uma avaliação
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        return view('account.reviews.edit', [
            'review' => $review,
        ]);
    }

    // Atualiza uma avaliação existente
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Verifica se o usuário é o dono da avaliação
        if ($review->user_id != Auth::id()) {
            return redirect()->route('account.reviews')->withErrors(['Você não tem permissão para editar esta avaliação.']);
        }

        $validator = Validator::make($request->all(), [
            'review' => 'required|min:5|max:500',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review->update([
            'review' => $request->review,
            'status' => $request->status,
        ]);

        return redirect()->route('account.reviews')->with('success', 'Avaliação atualizada com sucesso!');
    }

    // Remove uma avaliação do banco de dados
    public function deleteReview(Request $request)
    {
        $id = $request->id;
        $review = Review::find($id);
        if ($review == null) {
            return response()->json([
                'success' => false,
                'message' => 'Avaliação não encontrada.',
            ]);
        }
        // Verifica se o usuário é o dono da review
        if ($review->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para excluir esta avaliação.',
            ]);
        }
        $review->delete();
        return response()->json([
            'success' => true,
            'message' => 'Avaliação excluída com sucesso.',
        ]);
    }

}
