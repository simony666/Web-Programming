<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------
// Month  Sales
// -----  -----
// Jan    10.00
// Feb    20.00

if (req('data')) {
    $year = req('year');

    // TODO
    $stm = $db->prepare(
        "SELECT
            DATE_FORMAT(order_date, '%b'),
            SUM(total_cost)
        FROM orders
        WHERE YEAR(order_date) = ?
        GROUP BY MONTH(order_date)
    ");
    $stm->execute([$year]);
    $data = $stm->fetchAll(PDO::FETCH_NUM);

    json($data);
}

// Page data ----------------------------------------------

// TODO: Populate select list
$year = req('year');

$years = $db->query(
    "SELECT DISTINCT
        YEAR(order_date),
        YEAR(order_date)
    FROM orders
    ORDER BY YEAR(order_date) DESC
")->fetchAll(PDO::FETCH_KEY_PAIR);

// ----------------------------------------------------------------------------

$_title = 'Overall Sales by Month';
include '../_head.php';
?>

<p>
    <!-- TODO -->
    <?= select('year', $years, null, false) ?>
</p>

<div id="chart" style="width: 800px; height: 400px"></div>

<p>
    <!-- Testing -->
    <a href="?data=1&year=2020" target="data">Data</a>
</p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;

    function init() {
        dt = new google.visualization.DataTable();
        // TODO: Columns
        dt.addColumn('string', 'Month');
        dt.addColumn('number', 'Sales');

        const style = {
            bold: true,
            italic: false,
            fontSize: 20,
            color: 'purple',
        };

        opt = {
            title: 'TODO',
            fontName: 'Roboto',
            fontSize: 16,
            titleTextStyle: { 
                fontSize: 20,
            },
            chartArea: {
                width: '85%',
                height: '70%',
                top: 60,
                left: 100,
            },
            legend: 'none',
            vAxis: {
                title: 'Sales (RM)',
                titleTextStyle: style,
            },
            hAxis: {
                title: 'Month',
                titleTextStyle: style,
            },
            animation: {
                duration: 500,
                startup: true,
            },
            colors: ['tomato'],
        };

        cht = new google.visualization.ColumnChart($('#chart')[0]);

        $('#year').change();
    }

    $('#year').change(e => {
        e.preventDefault();

        // TODO: Set param
        const param = {
            year: $('#year').val(),
        };

        $.getJSON('?data=1', param, data => {
            // TODO: Set title
            opt.title = 'Overall Sales by Month - ' + param.year;

            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);

            // Format sales (column 1) --> tooltip
            new google.visualization
                      .NumberFormat({ pattern: 'RM #,##0.00' })
                      .format(dt, 1);

            cht.draw(dt, opt);
        });
    });
</script>

<?php
include '../_foot.php';