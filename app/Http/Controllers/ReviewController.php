<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
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

        $review = new Review();
        $review->book_id = $request->book_id;
        $review->user_id = Auth::id();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Avaliação salva com sucesso!',
            'redirect' => route('book.detail', $request->book_id)
        ]);
    }
}
