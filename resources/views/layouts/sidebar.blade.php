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
                        <ul class="nav flex-column">
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('books.index') }}">Livros</a>                               
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('account.reviews') }}">Avaliações</a>                               
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('account.profile') }}">Profile</a>                               
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('account.myReviews') }}">Minhas Avaliações</a>
                    </li>
                    <li class="nav-item">
                        <a href="change-password.html">Trocar Senha</a>
                    </li> 
                    @auth
                    <li class="nav-item">
                        <a href="{{ route('account.logout') }}">Logout</a>
                    </li>
                    @endauth
                </ul>
                    </div>
                </div>
