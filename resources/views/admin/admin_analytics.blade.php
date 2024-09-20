<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Analytics</title>
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

    <h1>Total Examinees ({{ $number_of_examiners }})</h1>
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

    <h1>Top Notchers ({{ $number_of_top_notchers }})</h1>
    <select id="yearSelector" onchange="filterTopNotchers()">
        <option value="">Select Year</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
    </select>
    <table>
        <thead>
            <tr>
                <th>Default ID</th>
                <th>Name</th>
                <th>Score</th>
                <th>Number of items</th>
            </tr>
        </thead>
        <tbody id="topNotchersBody">
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

    <h1>Number of Examiners based on Gender ({{ $number_of_top_notchers }}) </h1>
    <canvas id="genderChart" width="300" height="50"></canvas>

    <br>
    <br>
    <br>

    <h1>Available Course ({{ $number_of_course }})</h1>
    <canvas id="coursesChart" width="300" height="300"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    {{-- FILTERING TOP NOTCHERS --}}
    <script>
        function filterTopNotchers() {
            const selectedYear = document.getElementById('yearSelector').value;
            const tbody = document.getElementById('topNotchersBody');
            tbody.innerHTML = '';
        
            if (selectedYear) {
                fetch(`/admin/top-notchers/${selectedYear}`)
                    .then(response => response.json())
                    .then(data => {
                        data.topNotchers.forEach(notcher => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${notcher.default_id}</td>
                                <td>${notcher.fullname}</td>
                                <td>${notcher.total_points}</td>
                                <td>${notcher.number_of_items}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error fetching top notchers:', error));
            } else {
            }
        }
        </script>
    
    
</body>
</html>
