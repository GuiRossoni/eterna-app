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
                        Editar Livro
                    </div>
                    <div class="card-body">
                        <form action="{{ route('books.update.post', $book->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Título</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Título" name="title" id="title" value="{{ old('title', $book->title) }}" />
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Autor</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" placeholder="Autor"  name="author" id="author" value="{{ old('author', $book->author) }}"/>
                                @error('author')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Descrição</label>
                                <textarea name="description"  id="description" class="form-control" placeholder="Descrição" cols="30" rows="5">{{ old('description', $book->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="Image" class="form-label">Capa</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"  name="image" id="image"/>
                                @error('image')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror

                                @if (!empty($book->image))
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/books/' . $book->image) }}" alt="Capa do Livro" class="img-fluid" style="max-width: 100px;">
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <img src="{{ asset('images/default.png') }}" alt="Capa do Livro" class="img-fluid" style="max-width: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Estado</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{  ($book->status == 1) ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{  ($book->status == 0) ? 'selected' : '' }}>Oculto</option>
                                </select>
                            </div>

                            <button class="btn btn-primary mt-2">Salvar</button>
                        </form>                    
                    </div>
                </div> 
        </div>       
    </div>
@endsection