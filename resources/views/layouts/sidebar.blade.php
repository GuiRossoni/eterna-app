<ul class="nav flex-column">
    @if (Auth::user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ route('books.index') }}">Livros</a>                               
        </li>
        <li class="nav-item">
            <a href="reviews.html">Avaliações</a>                               
        </li>
    @endif
    <li class="nav-item">
        <a href="{{ route('account.profile') }}">Profile</a>                               
    </li>
    <li class="nav-item">
        <a href="my-reviews.html">Minhas Avaliações</a>
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