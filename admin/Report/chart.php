<?php
include '../../_/_base.php';
auth('Admin');

// Fetch gender data from the user table
$query = $db->query("SELECT gender, COUNT(*) as count FROM user WHERE gender IS NOT NULL GROUP BY gender");
$genderData = $query->fetchAll(PDO::FETCH_ASSOC);

// Generating data in a format suitable for Google Charts
$chartData = [['Gender', 'Count']];
foreach ($genderData as $row) {
    $chartData[] = [$row['gender'], intval($row['count'])]; // Convert count to integer
}

$_title = 'User Gender Distribution'; // Update title
include '../../_/layout/admin/header.php';
?>

<div id="chart" style="width: 800px; height: 400px"></div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?= json_encode($chartData) ?>);

        var options = {
            title: 'User Gender Distribution',
            legend: { position: 'top' },
            chartArea: {
                width: '50%',
                height: '70%',
                top: 60,
                left: 100,
            },
            vAxis: {
                title: 'Count', // Y-axis label
                format: '0', // Display integers on the y-axis
            },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
</script>

<?php
include '../../_/layout/admin/footer.php';
?>