@extends('layouts.app')
@section('main')

    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                @include('layouts.sidebar')               
            </div>
            <div class="col-md-9">
                
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Editar Minha Avaliação
                    </div>
                        <div class="card-body pb-3">
                            <form action="{{ route('account.myReviews.update', $review->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="review" class="form-label">Avaliação</label>
                                <textarea name="review" id="review" class="form-control @error('review') is-invalid @enderror" rows="5">{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nota</label>
                                <select name="rating" id="status" class="form-select @error('rating') is-invalid @enderror">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $review->status == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ $review->status == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ $review->status == 4 ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ $review->status == 5 ? 'selected' : '' }}>5</option>
                                </select>
                                @error('rating')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>  
                            <div>
                                <button class="btn btn-primary mt-2">Salvar</button>
                            </div>
                        </form>  
                        </div>
                </div>                
            </div>
        </div>       
    </div>

@endsection