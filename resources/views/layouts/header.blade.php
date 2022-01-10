@inject('auth', '\Illuminate\Support\Facades\Auth')
<div>
    <ul>
        @if ($auth::check())
            <li><a href="{{ route('profile') }}">Profile</a></li>
            <li><a href="{{ route('logout') }}">Logout</a></li>
        @else
            <li><a href="{{ route('sign_in') }}">Sign in</a></li>
            <li><a href="{{ route('sign_up') }}">Sign up</a></li>
        @endif
    </ul>
</div>