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
                        Livros
                    </div>
                    <div class="card-body pb-0">            
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Adicionar Livro</a> 
                                <form action="" method="GET">
                                    <div class="d-flex">
                                        <input type="text" class="form-control" value="{{ Request::get('keyword') }}" name="keyword" placeholder="Buscar">
                                        <button type="submit" class="btn btn-primary ms-2">Buscar</button>
                                        <a href="{{ route('books.index') }}" class="btn btn-secondary ms-2">Limpar</a>
                                    </div>
                                </form>                            
                        </div>
                        
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Avaliação</th>
                                    <th>Estado</th>
                                    <th width="150">Ação</th>
                                </tr>
                                <tbody>
                                    @if ($books->isNotEmpty())
                                        @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author }}</td>
                                        <td>3.0 (3 Reviews)</td>
                                        <td>
                                            @if ($book->status == 1)
                                                <span class="text-success">Ativo</span>
                                            @else
                                                <span class="text-danger">Oculto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" onclick="deleteBook({{ $book->id }});" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum Livro Disponível</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </thead>
                        </table>
                        {{ $books->links() }}             
                    </div>                    
                </div>                
            </div>
        </div>       
    </div>
@endsection

@section('script')

    <script>
        function deleteBook(id) {
            if (confirm("Você tem certeza que deseja excluir este livro?")) {
                $.ajax({
                    url: '{{ route("books.destroy", ":id") }}'.replace(':id', id),
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        window.location.href = "{{ route('books.index') }}";
                    }
                });
            }
        }

    </script>

@endsection