<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    // Lista os livros com filtros e paginação
    public function index(Request $request)
    {
        $books = Book::orderBy('created_at','DESC');

        if (!empty($request->keyword))
        {
            $books->where('title', 'like', '%' . $request->keyword . '%')
                ->orWhere('author', 'like', '%' . $request->keyword . '%');
        }

        $books = $books->withCount('reviews')->withSum('reviews', 'rating')->paginate(10);
        
        return view('books.list',[
            'books' => $books,
        ]);
    }

    // Exibe o formulário de cadastro de livro
    public function create()
    {
        return view('books.create');
    }

    // Salva um novo livro no banco de dados
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|min:5|max:150',
            'author' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?! )[A-Za-zÀ-ÿ ]+$/u'
            ],
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:1,0',
        ];

        if(!empty($request->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg|max:2048';
        }

        $messages = [
            'author.regex' => 'O nome do autor deve conter apenas letras.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('books.create')
                ->withInput()
                ->withErrors($validator);
        }

        // Criação do livro
        $book = Book::create($request->only(['title', 'author', 'description', 'status']));

        // Salva imagem do livro, se enviada
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);

            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/books/' . $imageName));
            $img->resize(990, 1500);
            $img->save(public_path('uploads/books/thumb/' . $imageName));
        }

        return redirect()->route('books.index')
                ->with('success', 'Livro adicionado com sucesso!');

    }

    // Exibe o formulário de edição de um livro
    public function edit($id)
    {
        $book = Book::find($id);
        return view('books.edit', [
            'book' => $book,
        ]);
    }

    // Atualiza os dados de um livro existente
    public function update($id, Request $request)
    {
        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|string|min:5|max:150',
            'author' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?! )[A-Za-zÀ-ÿ ]+$/u'
            ],
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:1,0',
        ];

        if(!empty($request->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg|max:2048';
        }

        $messages = [
            'author.regex' => 'O nome do autor deve conter apenas letras.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)
                ->withInput()
                ->withErrors($validator);
        }

        // Atualiza os dados do livro
        $book->update($request->only(['title', 'author', 'description', 'status']));

        // Atualiza imagem do livro, se enviada
        if (!empty($request->image)) 
        {
            File::delete(public_path('uploads/books/' . $book->image));
            File::delete(public_path('uploads/books/thumb/' . $book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);

            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/books/' . $imageName));
            $img->resize(990, 1500);
            $img->save(public_path('uploads/books/thumb/' . $imageName));
        }

        return redirect()->route('books.index')
                ->with('success', 'Livro atualizado com sucesso!');
    }

    // Remove um livro do banco de dados
    public function destroy(Request $request)
    {
        $book = Book::find($request->id);
        if ($book == null)
        {
            session()->flash('error', 'Livro não encontrado!');
            return response()->json([
                'status' => false,
                'message' => 'Livro não encontrado!'
            ]);
        }
        else
        {
            File::delete(public_path('uploads/books/' . $book->image));
            File::delete(public_path('uploads/books/thumb/' . $book->image));
            $book->delete();
            session()->flash('success', 'Livro removido com sucesso!');
            return response()->json([
                'status' => true,
                'message' => 'Livro removido com sucesso!'
            ]);
        }
    }
}
