@extends('layouts.app')

@section('main')
    <div class="container mt-3 ">
        <div class="row justify-content-center d-flex mt-5">
            <div class="col-md-12">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark ">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp; <strong>Voltar</strong>
                </a>
                <div class="row mt-4">
                    <div class="col-md-4">
                        @if ($book->image != '')
                            <img src="{{ asset('uploads/books/thumb/' . $book->image) }}" alt="" class="card-img-top">
                        @else
                            <img src="https://placehold.co/900x1366?text=Sem Imagem" alt="" class="card-img-top">
                        @endif
                    </div>
                        @php
                            if($book->reviews_count > 0) {
                                $avgRating = $book->reviews_sum_rating / $book->reviews_count;
                            } else {
                                $avgRating = 0;
                            }
                                $avgRatingPer = ($avgRating*100) / 5;
                            @endphp                    
                    <div class="col-md-8">
                        @if(session('error_review'))
                            <div class="alert alert-danger" id="error-review-alert">
                                {{ session('error_review') }}
                            </div>
                        @endif
                        @if(session('success_review'))
                            <div class="alert alert-success" id="success-review-alert">
                                {{ session('success_review') }}
                            </div>
                        @endif
                        <h3 class="h2 mb-3">{{ $book->title }}</h3>
                        <div class="h4 text-muted">{{ $book->author }}</div>
                        <div class="star-rating d-inline-flex ml-2" title="">
                            <span class="rating-text theme-font theme-yellow">{{ number_format($avgRating,1) }}</span>
                            <div class="star-rating d-inline-flex mx-2" title="">
                                <div class="back-stars ">
                                    <i class="fa fa-star " aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="theme-font text-muted">({{ ($book->reviews_count > 1) ? $book->reviews_count.' Avaliações' : $book->reviews_count.' Avaliação' }} )</span>
                        </div>

                        <div class="content mt-3">
                            {{ $book->description }}
                        </div>
                        <div class="col-md-12 pt-2">
                            <hr>
                        </div>
                        <div class="row pb-5">
                            <div class="col-md-12  mt-4">
                                <div class="d-flex justify-content-between">
                                    <h3>Reviews</h3>
                                    <div>
                                        @if (Auth::check())
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Criar Review
                                          </button>
                                        @else
                                            <a href="{{ route('account.login') }}" class="btn btn-primary">Criar Review</a>
                                        @endif                                         
                                    </div>
                                </div>                        
                            @if ($book->reviews->isNotEmpty())
                                @foreach ($book->reviews as $review)
                                    <div class="card border-0 shadow-lg my-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-3">{{ $review->user->name }}</h4>
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d \d\e F \d\e Y') }}
                                            </span>         
                                        </div>
                                       @php
                                            $ratingPer = ($review->rating / 5) * 100;
                                       @endphp
                                        <div class="mb-3">
                                            <div class="star-rating d-inline-flex" title="">
                                                <div class="star-rating d-inline-flex " title="">
                                                    <div class="back-stars ">
                                                        <i class="fa fa-star " aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                    
                                                        <div class="front-stars" style="width: {{ $ratingPer }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                                               
                                        </div>
                                        <div class="content">
                                            <p>{{ $review->review }}</p>
                                        </div>
                                    </div>
                                    </div>  
                                @endforeach
                                @else
                                    <div>
                                        Sem Avaliações
                                    </div>
                            @endif     
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>   
    
    <!-- Modal -->
    <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Criar avaliação para: {{ $book->title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bookReviewForm" name="bookReviewForm">
                        <input type="hidden" name="book_id" id="book_id" value="{{ $book->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="review" class="form-label">Avaliação</label>
                            <textarea name="review" id="review" class="form-control" cols="5" rows="5" placeholder="Avaliação"></textarea>
                            <p class="invalid-feedback" id="review-error"></p>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Nota</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $("#bookReviewForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('books.review.post') }}",
            type: "POST",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: $("#bookReviewForm").serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    var errors = response.errors;
                    if (errors.review) {
                        $("#review").addClass("is-invalid");
                        $("#review-error").html(errors.review[0]);
                    } else {
                        $("#review").removeClass("is-invalid");
                        $("#review-error").html('');
                    }
                }
            }
        });
    });

    $(document).ready(function() {
        setTimeout(function() {
            $("#success-review-alert, #error-review-alert").fadeOut('slow');
        }, 3000);
    });
</script>
@endsection