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
    <label for="">Total Results: {{ $get_total_results }} </label> <br><br>

    <h1>Yearly Examinees</h1>
    
    <canvas id="yearlyExaminees" width="300" height="50"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('yearlyExaminees').getContext('2d');
        const examineesData = @json($examinees);
        const years = examineesData.map(e => e.year);
        const counts = examineesData.map(e => e.examinee_count);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: years,
                datasets: [{
                    label: '# of Examinees',
                    data: counts,
                    backgroundColor: '#461111',
                    borderColor: '#461111',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
    
    
</body>
</html>
