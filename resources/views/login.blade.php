<!-- resources/views/welcome.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Socialite Testing Example</title>
</head>
<body>
    <h1>Laravel Socialite Testing Example</h1>
    @if (auth()->check())
        <p>User is authenticated.</p>
        <img style="width: 100px; height: 100px; border-radius: 100%;" src="{{ auth()->user()->avatar }}" />
        <p>Name: {{ auth()->user()->name }}</p>
        <p>Email: {{ auth()->user()->email }}</p>

        <p><a href="{{ route('logout') }}">Logout</a></p>
    @else
        <p>
            <a href="{{ route('login', [ 'provider' => 'google' ]) }}">Login with Google</a>
        </p>
        
        <p>
            <a href="{{ route('login', [ 'provider' => 'github' ]) }}">Login with github</a>
        </p>

        <p>
            <a href="{{ route('login', [ 'provider' => 'slack' ]) }}">Login with slack</a>
        </p>
        
        <p>
            <a href="{{ route('login', [ 'provider' => 'facebook' ]) }}">Login with meta</a>
        </p>
    @endif
</body>
</html>