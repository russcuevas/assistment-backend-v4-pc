<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login Page</h1>
    <form action="{{ route('examiners.login.request') }}" method="POST">
        @csrf
        <label for="">Default ID</label>
        <input type="text" name="default_id"><br>
        <label for="">Password</label>
        <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>