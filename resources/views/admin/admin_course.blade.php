<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <a href="{{ route('adminlogout') }}">Logout</a><br>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a><br>
    <a href="{{ route('admin.examiners.account') }}">Examiners Management</a><br>
    <a href="{{ route('admin.course') }}">Course List</a><br>
    <a href="{{ route('admin.questionnaire') }}">Add exam questions</a><br>
    <a href="{{ route('admin.analytics.page') }}">Analytics</a>
    <hr>
    <h1>Course Page</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('addcourse') }}" method="POST">
        @csrf
        <label for="">Course</label>
        <input type="text" name="course"><br>
        <input type="submit" value="Add course">
    </form>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Available Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($available_courses as $available_course)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $available_course->course }}</td>
                    <td>
                        <a href="{{ route('editcourse', $available_course->id) }}">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('deletecourse', $available_course->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this course?');">
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No courses available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>