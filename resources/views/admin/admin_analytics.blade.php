<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <a href="{{ route('adminlogout') }}">Logout</a><br>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a><br>
    <a href="{{ route('admin.examiners.account') }}">Examiners Management</a><br>
    <a href="{{ route('admin.course') }}">Course List</a><br>
    <a href="{{ route('admin.questionnaire') }}">Add exam questions</a><br>
    <a href="{{ route('admin.analytics.page') }}">Analytics</a>
    <hr>
    <h1>Analytics Page</h1>

    <h1>Total Examinees</h1>
    <table>
        <thead>
            <tr>
                <th>Default ID</th>
                <th>Fullname</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Birthday</th>
                <th>Strand</th>
                <th>Chosen Course</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($total_examiners as $examiner)
                @if(!is_null($examiner->fullname) && !empty($examiner->fullname))
                    <tr>
                        <td>{{ $examiner->default_id }}</td>
                        <td>{{ $examiner->fullname }}</td>
                        <td>{{ $examiner->gender }}</td>
                        <td>{{ $examiner->age }}</td>
                        <td>{{ $examiner->birthday }}</td>
                        <td>{{ $examiner->strand }}</td>
                        <td>
                            1.) {{ $examiner->course_1_name ?? 'N/A' }} <br>
                            2.) {{ $examiner->course_2_name ?? 'N/A' }} <br>
                            3.) {{ $examiner->course_3_name ?? 'N/A' }} <br>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8">No examiners available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h1>Top Notchers</h1>
    {{-- Top Notchers --}}
    <table>
        <thead>
            <tr>
                <th>Default ID</th>
                <th>Name</th>
                <th>Score</th>
                <th>Number of items</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topNotchers as $notcher)
                <tr>
                    <td>{{ $notcher->default_id }}</td>
                    <td>{{ $notcher->fullname }}</td>
                    <td>{{ $notcher->total_points }}</td>
                    <td>{{ $notcher->number_of_items }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
    <br>
    <br>

    <h1>Number of Examiners based on Gender</h1>
    <canvas id="genderChart" width="300" height="50"></canvas>

    <br>
    <br>
    <br>

    <h1>Available Course</h1>
    <canvas id="coursesChart" width="300" height="300"></canvas>


    <script>
        fetch('{{ route('admin.available.courses') }}')
            .then(response => response.json())
            .then(data => {
                const courseCounts = data.availableCourses;
                const labels = Object.keys(courseCounts);
                const values = Object.values(courseCounts);

                const chartData = {
                    labels: labels,
                    datasets: [{
                        label: 'Available Courses Distribution',
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                };

                const config = {
                    type: 'pie',
                    data: chartData,
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Available Course',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        return `${label}`;
                                    },
                                    title: function (content) {
                                        return '';
                                    }
                                }
                            }
                        }
                    }
                };

                const ctx = document.getElementById('coursesChart').getContext('2d');
                new Chart(ctx, config);
            })
            .catch(error => {
                console.error('Error fetching available courses data:', error);
            });
    </script>

    <script>
        const genderCounts = @json($genderCounts);
        const data = {
            labels: Object.keys(genderCounts),
            datasets: [{
                label: 'Number of Examiners based on Gender',
                data: Object.values(genderCounts),
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    label += context.raw;
                                }
                                return label;
                            },
                            title: function(context) {
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const ctx = document.getElementById('genderChart').getContext('2d');
        new Chart(ctx, config);
    </script>
</body>
</html>
