<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>

    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('forgotpasswordrequest') }}" method="POST">
        @csrf
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required><br>
        <input type="submit" value="Send Reset Link">
    </form>

    <p>
        <a href="{{ route('adminloginpage') }}">Back to Login</a>
    </p>
</body>
</html>
