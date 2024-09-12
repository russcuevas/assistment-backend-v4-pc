<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examiners Dashboard</title>
</head>
<body>
    <h1>This is examiners dashboard</h1>
    <a href="{{ route('examinerslogout') }}">Logout</a><br>
    <a href="{{ route('examiners.dashboard.page') }}">Results</a><br>

    <hr>

    <h1>Hi {{ $user->fullname }} ( {{ $user->default_id }} )</h1>

    @if($courseDataWithSuggestions->isEmpty())
        <p>No data available.</p>
    @else
    <h2>Suggested course based on your preference</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Course</th>
                <th>Points</th>
                <th>Over</th>
                <th>Percentage (%)</th>
                <th>Suggestions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courseDataWithSuggestions as $data)
                <tr>
                    <td>{{ $data['course_name'] }}</td>
                    <td>{{ $data['points'] }}</td>
                    <td>{{ $data['over_points'] }}</td>
                    <td>{{ $data['percentage'] }}%</td>

                    <td>
                        @if($data['percentage'] > 80)
                            <span style="color: green;">Highly Recommended</span>
                        @elseif($data['percentage'] > 50)
                            <span style="color: orange;">Recommended</span>
                        @else
                            <span style="color: red;">Consider Reviewing</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>SUGGESTED COURSE BASED ON YOUR PREFERENCE (50% and above)</h3>
    <ul>
        @foreach($courseDataWithSuggestions->filter(function ($course) {
            return $course['percentage'] > 50;
        }) as $data)
            <li>
                {{ $data['course_name'] }} - {{ $data['percentage'] }}% 
                @if($data['percentage'] > 80)
                    <span style="color: green;">(Highly Recommended)</span>
                @else
                    <span style="color: orange;">(Recommended)</span>
                @endif
            </li>
        @endforeach
    </ul>

    <h3>YOUR TOP CHOSEN COURSES</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Course</th>
                <th>Points</th>
                <th>Over</th>
                <th>Percentage (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCourses as $data)
                <tr>
                    <td>{{ $data['course_name'] }}</td>
                    <td>{{ $data['points'] }}</td>
                    <td>{{ $data['over_points'] }}</td>
                    <td>{{ $data['percentage'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @endif

    <h1>Tips per program that is being recommended according to your strand</h1>
</body>
</html>
