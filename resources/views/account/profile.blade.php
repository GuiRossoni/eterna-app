@extends('layouts.app')
@section('main')
<div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-lg">
                    <div class="card-header  text-white">
                        Olá, {{ Auth::user()->name }}                        
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if (Auth::user()->image != "")
                                <img src="{{ asset('uploads/profileImg/thumb/' . Auth::user()->image) }}" class="img-fluid rounded-circle" alt="Foto de Perfil">
                            @else
                                <img src="{{ asset('images/default.png') }}" class="img-fluid rounded-circle" alt="Foto de Perfil">
                            @endif
                        </div>
                        <div class="h5 text-center">
                            <strong>{{ Auth::user()->name }}</strong>
                            <p class="h6 mt-2 text-muted">5 Reviews</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-lg mt-3">
                    <div class="card-header  text-white">
                        Painel
                    </div>
                    <div class="card-body sidebar">
                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Profile
                    </div>
                    <div class="card-body">
                        <form action="{{ route('account.updateProfile.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" name="name" id="" />
                                @error('name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Email</label>
                                <input type="text" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" id="email"/>
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label @error('image') is-invalid @enderror">Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                                @error('image')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                                <!--<img src="images/profile-img-1.jpg" class="img-fluid mt-4" alt="Imagem" > -->
                            </div>   
                            <button class="btn btn-primary mt-2">Atualizar</button>
                        </form>                  
                    </div>
                </div>                
            </div>
        </div>       
    </div>
@endsection