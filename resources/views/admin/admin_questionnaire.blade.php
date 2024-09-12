<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
    <a href="{{ route('admin.questionnaire') }}">Add exam questions</a><br>
    <a href="{{ route('admin.analytics.page') }}">Analytics</a>
    <hr>
    <h1>Questionnaire Page</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('admin.add.questionnaire') }}" method="POST">
    @csrf
    <label for="question_text">Question</label>
    <input type="text" name="question_text"> <br>

    <label for="">Question for?</label>
    <select name="question_subject" id="question_subject" required>
        <option value="Math">Math</option>
        <option value="English">English</option>
        <option value="Science">Science</option>
    </select><br>


    <label for="option_text_a">Choices</label><br>
    <label for="option_text_a">A</label>
    <input type="text" name="option_text[]" id="option_text_a" required><br>

    <label for="option_text_b">B</label>
    <input type="text" name="option_text[]" id="option_text_b" required><br>

    <label for="option_text_c">C</label>
    <input type="text" name="option_text[]" id="option_text_c" required><br>

    <label for="option_text_d">D</label>
    <input type="text" name="option_text[]" id="option_text_d" required><br>

    <label for="is_correct">Answer</label>
    <select name="is_correct" id="is_correct" required>
        <option value="1">A</option>
        <option value="1">B</option>
        <option value="1">C</option>
        <option value="1">D</option>
    </select><br>

    <label for="courses">Courses</label><br>
    @foreach($courses as $course)
        <input type="checkbox" name="course_id[]" value="{{ $course->id }}" id="course_{{ $course->id }}">
        <label for="course_{{ $course->id }}">{{ $course->course }}</label><br>
    @endforeach
    <input type="submit" value="Add question">
    </form>
    


    <h2>Math Questions</h2>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Question For?</th>
                <th>Choices</th>
                <th>Related Course</th>
                <th>Correct Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($questions->filter(fn($q) => $q->first()->question_subject === 'Math') as $questions_array)
            @php
                $question = $questions_array->first();
                $choices = $questions_array->pluck('option_text')->unique();
                $correct_answer = $questions_array->where('is_correct', true)->pluck('option_text')->first() ?? 'None';
                $related_course = $questions_array->pluck('course_name')->unique();
            @endphp
            <tr>
                <td>{{ $question->question_text }}</td>
                <td>{{ $question->question_subject }}</td>
                <td>
                    @foreach($choices as $choice)
                        {{ $choice }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($related_course as $related_courses)
                        {{ $related_courses }}<br>
                    @endforeach
                </td>
                <td>{{ $correct_answer }}</td>
                <td>
                    <a href="{{ route('admin.questionnaire.edit', $question->question_id) }}">Edit</a>
                        <form action="{{ route('admin.questionnaire.delete', $question->question_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                        </form>
                </td>
            </tr>
        @empty
            <tr>
                <td>No questions for math</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h2>English Questions</h2>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Question For?</th>
                <th>Choices</th>
                <th>Related Course</th>
                <th>Correct Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($questions->filter(fn($q) => $q->first()->question_subject === 'English') as $questions_array)
            @php
                $question = $questions_array->first();
                $choices = $questions_array->pluck('option_text')->unique();
                $correct_answer = $questions_array->where('is_correct', true)->pluck('option_text')->first() ?? 'None';
                $related_course = $questions_array->pluck('course_name')->unique();
            @endphp
            <tr>
                <td>{{ $question->question_text }}</td>
                <td>{{ $question->question_subject }}</td>
                <td>
                    @foreach($choices as $choice)
                        {{ $choice }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($related_course as $related_courses)
                        {{ $related_courses }}<br>
                    @endforeach
                </td>
                <td>{{ $correct_answer }}</td>
                <td>
                        <a href="{{ route('admin.questionnaire.edit', $question->question_id) }}">Edit</a>
                        <form action="{{ route('admin.questionnaire.delete', $question->question_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Display Science Questions --}}
        <h2>Science Questions</h2>
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Question For?</th>
                    <th>Choices</th>
                    <th>Related Course</th>
                    <th>Correct Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($questions->filter(fn($q) => $q->first()->question_subject === 'Science') as $questions_array)
                @php
                    $question = $questions_array->first();
                    $choices = $questions_array->pluck('option_text')->unique();
                    $correct_answer = $questions_array->where('is_correct', true)->pluck('option_text')->first() ?? 'None';
                    $related_course = $questions_array->pluck('course_name')->unique();
                @endphp
                <tr>
                    <td>{{ $question->question_text }}</td>
                    <td>{{ $question->question_subject }}</td>
                    <td>
                        @foreach($choices as $choice)
                            {{ $choice }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($related_course as $related_courses)
                            {{ $related_courses }}<br>
                        @endforeach
                    </td>
                    <td>{{ $correct_answer }}</td>
                    <td>
                        <a href="{{ route('admin.questionnaire.edit', $question->question_id) }}">Edit</a>
                        <form action="{{ route('admin.questionnaire.delete', $question->question_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        this.nextElementSibling.classList.add('checked');
                    } else {
                        this.nextElementSibling.classList.remove('checked');
                    }
                });
            });
        });
    </script>
</body>
</html>
