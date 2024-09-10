<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Landing</title>
</head>
<body>
<h1>Welcome, {{ $examiners->default_id }}</h1>
<a href="{{ route('examinerslogout') }}">Logout</a><br>
    <form action="{{ route('examiners.add.information') }}" method="POST">
        @csrf
        <h1>Personal Details</h1>
        <label for="">Fullname</label>
        <input type="text" name="fullname"><br>
        <label for="">Gender</label>
        <input type="text" name="gender"><br>
        <label for="">Age</label>
        <input type="text" name="age"><br>
        <label for="">Birthday</label>
        <input type="date" name="birthday"><br>
        <label for="">Strand</label>
        <input type="text" name="strand"><br>

        <h1>Top 3 Courses</h1>
        <label for="">Course 1</label>
        <select name="course_1">
            <option value="">Select a course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->course }}</option>
            @endforeach
        </select><br>
        <label for="">Course 2</label>
        <select name="course_2">
            <option value="">Select a course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->course }}</option>
            @endforeach
        </select><br>
        <label for="">Course 3</label>
        <select name="course_3">
            <option value="">Select a course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->course }}</option>
            @endforeach
        </select><br>
        <button type="submit">Submit</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelects = document.querySelectorAll('select[name^="course_"]');

            function updateOptions() {
                const selectedCourses = Array.from(courseSelects)
                    .map(select => select.value)
                    .filter(value => value);

                courseSelects.forEach(select => {
                    const options = Array.from(select.querySelectorAll('option'));
                    options.forEach(option => {
                        option.style.display = selectedCourses.includes(option.value) && option.value !== '' ? 'none' : 'block';
                    });
                });
            }

            courseSelects.forEach(select => {
                select.addEventListener('change', updateOptions);
            });

            updateOptions();
        });
    </script>

</body>
</html>
