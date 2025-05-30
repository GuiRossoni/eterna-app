@extends('layouts.app')
@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
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
                        </thead>
                        <tbody>
                            @if ($books->isNotEmpty())
                                @foreach ($books as $book)
                                    @php
                                        if($book->reviews_count > 0) {
                                            $avgRating = $book->reviews_sum_rating / $book->reviews_count;
                                        } else {
                                            $avgRating = 0;
                                        }
                                            $avgRatingPer = ($avgRating*100) / 5;
                                    @endphp
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td> {{ number_format($avgRating,1) }} ({{ ($book->reviews_count > 1) ? $book->reviews_count.' Avaliações' : $book->reviews_count.' Avaliação' }} )</td>
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
                    </table>
                    {{ $books->links() }}             
                </div>                    
            </div>                
        </div>
    </div>       
</div>
@endsection

@section('scripts')

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