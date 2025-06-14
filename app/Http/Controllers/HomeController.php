<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Exibe a página inicial com a listagem de livros
    public function index(Request $request)
    {
        $books = Book::withCount('reviews')->withSum('reviews', 'rating')->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $books->where('title', 'like', '%' . $request->keyword . '%')
                ->orWhere('author', 'like', '%' . $request->keyword . '%');
        }

        $books = $books->where('status', 1)->paginate(8);

        return view('home', [
            'books' => $books,
        ]);
    }

    // Exibe os detalhes de um livro, incluindo avaliações
    public function detail($id, Request $request)
    {
        $book = Book::with(['reviews.user', 'reviews' => function($query){
            $query->where('status', 1)
                  ->orderBy('created_at', 'DESC');
        }])->withCount('reviews')->withSum('reviews', 'rating')->findOrFail($id);

        // Se o livro estiver inativo, retorna 404
        if ($book->status == 0) {
            abort(404);
        }

        // Mensagens de sucesso ou erro para avaliações
        if ($request->has('success_review')) {
            session()->flash('success_review', $request->get('success_review'));
        }
        if ($request->has('error_review')) {
            session()->flash('error_review', $request->get('error_review'));
        }

        return view('book-detail', [
            'book' => $book
        ]);
    }
}
