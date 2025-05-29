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
                        Editar Avaliação
                    </div>
                        <div class="card-body pb-3">
                            <form action="{{ route('account.reviews.update', $review->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="review" class="form-label">Avaliação</label>
                                <textarea name="review" id="review" class="form-control @error('review') is-invalid @enderror" rows="5">{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Oculto</option>
                                </select>
                                @error('status')
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