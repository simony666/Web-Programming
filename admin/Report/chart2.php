<?php
include '../../_/_base.php';

// Fetch date, successful login count, and failed login count aggregated by day from the login table in s3_database
$query = $db->query("
    SELECT 
        DATE(datetime) as login_date, 
        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_logins,
        SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_logins
    FROM s3_database.login 
    GROUP BY login_date
");
$loginData = $query->fetchAll(PDO::FETCH_ASSOC);

// Generating data in a format suitable for Google Charts for the line chart
$chartData = [['Login Date', 'Successful Logins', 'Failed Logins']];
foreach ($loginData as $row) {
    $chartData[] = [
        $row['login_date'],
        (int)$row['successful_logins'],
        (int)$row['failed_logins']
    ];
}

// Generating data for total login count
$totalLoginData = [['Datetime', 'Total Logins']];
foreach ($loginData as $row) {
    $totalLoginData[] = [
        $row['login_date'],
        (int)$row['successful_logins'] + (int)$row['failed_logins']
    ];
}

$_title = 'Login Data Visualization'; // Update title
include '../../_/layout/admin/header.php';
?>

<div id="chart" style="width: 800px; height: 400px"></div>
<button onclick="toggleChart()">Toggle Chart</button>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    var data = <?= json_encode($chartData) ?>;
    var totalData = <?= json_encode($totalLoginData) ?>;
    var currentData = data;
    var chartTitle = 'Successful vs Failed Logins';

    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var chartData = google.visualization.arrayToDataTable(currentData);

        var options = {
            title: chartTitle,
            legend: 'top',
            chartArea: {
                width: '85%',
                height: '70%',
                top: 60,
                left: 100,
            },
            series: {
                0: { color: 'green' }, // Successful logins color
                1: { color: 'red' }    // Failed logins color
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart'));
        chart.draw(chartData, options);
    }

    function toggleChart() {
        if (currentData === data) {
            currentData = totalData;
            chartTitle = 'Total Logins';
        } else {
            currentData = data;
            chartTitle = 'Successful vs Failed Logins';
        }

        drawChart();
    }
</script>

<?php
include '../../_/layout/admin/footer.php';
?>