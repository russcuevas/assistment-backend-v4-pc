<!DOCTYPE html>
<html>
<head>
    <title>Examination Complete</title>
</head>
<body>
    <h1>Examination Complete</h1>

    <h1>Hi {{ $user->fullname }} ( {{ $user->default_id }} )</h1>

    @if($courseDataWithSuggestions->isEmpty())
    <p>No data available.</p>
    @else
    <h2>YOUR SCORES AND PERCENTAGE</h2>
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

</body>
</html>
