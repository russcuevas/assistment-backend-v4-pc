<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>This is admin dashboard</h1>
    <a href="{{ route('adminlogout') }}">Logout</a><br>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a><br>
    <a href="{{ route('admin.examiners.account') }}">Examiners Management</a><br>
    <a href="{{ route('admin.course') }}">Course List</a><br>
    <a href="{{ route('admin.questionnaire') }}">Add exam questions</a><br>
    <a href="{{ route('admin.analytics.page') }}">Analytics</a>

    <hr>
    <br>
    <label for="">Total Admin: {{ $get_total_admin }} </label> <br>
    <label for="">Total Examinees: {{ $get_total_examinees }} </label> <br>
    <label for="">Total Results: </label>
</body>
</html>