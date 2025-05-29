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
                        Avaliações
                    </div>
                    <div class="card-body pb-0">
                    <div class="d-flex justify-content-end">
                        <form action="" method="GET">
                            <div class="d-flex">
                                <input type="text" class="form-control" value="{{ Request::get('keyword') }}" name="keyword" placeholder="Buscar">
                                <button type="submit" class="btn btn-primary ms-2">Buscar</button>
                                <a href="{{ route('account.reviews') }}" class="btn btn-secondary ms-2">Limpar</a>
                            </div>
                        </form>                            
                    </div>            
                        <table class="table table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Avaliação</th>
                                    <th>Livro</th>
                                    <th>Pontuação</th>
                                    <th>Criado</th>
                                    <th>Estado</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{ $review->review}} <br/><strong>{{ $review->user->name }}</strong></td>
                                            <td>{{ $review->book->title}}</td>
                                            <td>{{ $review->rating}}</td>
                                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}</td>
                                            <td>
                                                @if ($review->status == 1)
                                                    <span class="text-success">Ativo</span>
                                                @else
                                                    <span class="text-danger">Inativo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('account.reviews.edit', $review->id) }}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                                                <a href="#" onclick="event.preventDefault(); deleteReview({{ $review->id }});" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>   
                        {{ $reviews->links() }}
                    </div>
                    
                </div>                
            </div>
        </div>       
    </div>

@endsection

@section('scripts')
<script>
    function deleteReview(id) {
        if (confirm('Você tem certeza?')) {
            $.ajax({
                url: "{{ route('account.reviews.deleteReview') }}",
                data: {id: id, _method: 'DELETE'},
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        window.location.href = "{{ route('account.reviews') }}?success=" + encodeURIComponent(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
</script>
@endsection