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
                <div class="card-header text-white">
                    Alterar Senha
                </div>
                <div class="card-body">
                    <form action="{{ route('account.changePassword.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" id="old_password" placeholder="Senha Atual" />
                            @error('old_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" placeholder="Nova Senha" />
                            @error('new_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirme a Nova Senha</label>
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirme a Nova Senha" />
                        </div>
                        <button class="btn btn-primary mt-2">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection