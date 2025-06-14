<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{

    // Exibe o formulário de registro de usuário
    public function register() {
        return view('account.register');
    }

    // Processa o registro de um novo usuário
    public function processRegister(Request $request) {
        $messages = [
            'name.regex' => 'O nome deve conter apenas letras.',
        ];
        $validadtor = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?! )[A-Za-zÀ-ÿ ]+$/u'
            ],
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], $messages);

        if ($validadtor->fails()) {
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors($validadtor);
        }

        // Criação do usuário
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('account.login')
            ->with('success', 'Conta criada com sucesso! Faça login para continuar.');

    }

    // Exibe o formulário de login
    public function login() {
        return view('account.login');
    }

    // Processa o login do usuário
    public function processLogin(Request $request) {
        $validadtor = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8',
        ]);

        if ($validadtor->fails()) {
            return redirect()->route('account.login')
                ->withInput()
                ->withErrors($validadtor);
        }

        if (FacadesAuth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return redirect()->route('account.profile')
                ->with('success', 'Login realizado com sucesso!');
        } else {
            return redirect()->route('account.login')
                ->with('error', 'Credenciais inválidas.');
        }
    }

    // Exibe o perfil do usuário autenticado
    public function profile() {
        $user = User::find(Auth::user()->id);
        return view('account.profile', [
            'user' => $user
        ]);
    }

    // Atualiza os dados do perfil do usuário
    public function updateProfile(Request $request) {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?! )[A-Za-zÀ-ÿ ]+$/u'
            ],
            'email' => 'required|string|email|max:100|unique:users,email,' . Auth::user()->id,
        ];

        if (!empty($request->image)){
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validadtor = Validator::make($request->all(), $rules);

        if ($validadtor->fails()) {
            return redirect()->route('account.profile')
                ->withInput()
                ->withErrors($validadtor);
        }

        $user = User::find(Auth::id());
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        // Atualiza a imagem de perfil, se enviada
        if (!empty($request->image)) {

            File::delete(public_path('uploads/profileImg/' . $user->image));
            File::delete(public_path('uploads/profileImg/thumb/' . $user->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/profileImg'), $imageName);

            $user->image = $imageName;
            $user->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/profileImg/' . $imageName));
            $thumb = $img->cover(150, 150);
            $thumbPath = public_path('uploads/profileImg/thumb/' . $imageName);
            $thumb->save($thumbPath);
        }
        
        return redirect()->route('account.profile')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    // Realiza o logout do usuário
    public function logout() {
        Auth::logout();
        return redirect()->route('account.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    // Lista as avaliações feitas pelo usuário autenticado
    public function myReviews(Request $request) {
        if ($request->has('success')) {
            session()->flash('success', $request->get('success'));
        }

        $reviews = Auth::user()->reviews()->with('book')->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $reviews = $reviews->whereHas('book', function($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->keyword . '%');
            });
        }

        $reviews = $reviews->paginate(10);

        return view('account.my-reviews.list', [
            'reviews' => $reviews,
        ]);
    }

    // Exibe o formulário de edição de uma avaliação do usuário
    public function edit($id) {
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->firstOrFail();

        return view('account.my-reviews.edit', [
            'review' => $review,
        ]);
    }

    // Atualiza uma avaliação do usuário autenticado
    public function updateMyReview(Request $request, $id)
    {
        $review = Auth::user()->reviews()->where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'review' => 'required|min:5|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->route('account.myReviews')->with('success', 'Avaliação atualizada com sucesso!');
    }

    // Exibe o formulário de troca de senha
    public function changePasswordForm()
    {
        return view('account.change-password');
    }

    // Processa a troca de senha do usuário autenticado
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.changePassword')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('account.changePassword')
                ->withErrors(['old_password' => 'A senha atual está incorreta.'])
                ->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.profile')
            ->with('success', 'Senha alterada com sucesso!');
    }

}