<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <style>
        .checked {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body>
    <a href="{{ route('adminlogout') }}">Logout</a><br>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a><br>
    <a href="{{ route('admin.examiners.account') }}">Examiners Management</a><br>
    <a href="{{ route('admin.course') }}">Course List</a><br>
    <a href="{{ route('admin.questionnaire') }}">Add exam questions</a>
    <hr>
    <h1>Edit Question</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @elseif (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('admin.questionnaire.update', $questionData->question_id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="question_text">Question</label>
        <input type="text" name="question_text" value="{{ old('question_text', $questionData->question_text) }}" required> <br>

        <label for="question_subject">Question for?</label>
        <select name="question_subject" id="question_subject" required>
            <option value="Math" {{ old('question_subject', $questionData->question_subject) == 'Math' ? 'selected' : '' }}>Math</option>
            <option value="English" {{ old('question_subject', $questionData->question_subject) == 'English' ? 'selected' : '' }}>English</option>
            <option value="Science" {{ old('question_subject', $questionData->question_subject) == 'Science' ? 'selected' : '' }}>Science</option>
        </select><br>

        <label for="option_text_a">Choices</label><br>
        @foreach($choices as $index => $choice)
            <label for="option_text_{{ $index }}">Option {{ chr(65 + $index) }}</label>
            <input type="text" name="option_text[]" id="option_text_{{ $index }}" value="{{ old('option_text.' . $index, $choice) }}" required><br>
        @endforeach

        <label for="is_correct">Answer</label>
        <select name="is_correct" id="is_correct" required>
            @foreach($choices as $index => $choice)
                <option value="{{ $index }}" {{ old('is_correct', $correct_answer_index) == $index ? 'selected' : '' }}>
                    {{ chr(65 + $index) }}
                </option>
            @endforeach
        </select><br>

        <br>

        <label for="courses">Courses</label><br>
        @foreach($courses as $course)
            <input type="checkbox" name="course_id[]" value="{{ $course->id }}" id="course_{{ $course->id }}"
                {{ in_array($course->id, old('course_id', $related_course_ids)) ? 'checked' : '' }}>
            <label for="course_{{ $course->id }}">{{ $course->course }}</label><br>
        @endforeach

        <input type="submit" value="Update Question">
    </form>
    
    <hr>
</body>
</html>
