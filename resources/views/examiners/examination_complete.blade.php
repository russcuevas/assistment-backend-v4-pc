<!DOCTYPE html>
<html>
<head>
    <title>Examination Complete</title>
</head>
<body>
    <h1>Examination Complete</h1>

    <h1>Hi {{ $user->fullname }} ( {{ $user->default_id }} )</h1>

    <p>Thank you for examining.</p>

    <p>To view your results, please visit the following link: <a href="http://127.0.0.1:8000/examiners/dashboard">View Results</a></p>

</body>
</html>
