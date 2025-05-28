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
                        Adicionar Livro
                    </div>
                    <div class="card-body">
                        <form action="{{ route('books.store.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Título</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Título" name="title" id="title" value="{{ old('title') }}" />
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Autor</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" placeholder="Autor"  name="author" id="author" value="{{ old('author') }}"/>
                                @error('author')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Descrição</label>
                                <textarea name="description"  id="description" class="form-control" placeholder="Descrição" cols="30" rows="5">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="Image" class="form-label">Capa</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"  name="image" id="image"/>
                                @error('image')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Estado</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Ativo</option>
                                    <option value="0">Oculto</option>
                                </select>
                            </div>

                            <button class="btn btn-primary mt-2">Criar</button>
                        </form>                    
                    </div>
                </div> 
        </div>       
    </div>
@endsection