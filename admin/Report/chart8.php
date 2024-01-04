<?php
include '../_/_base.php';

// ----------------------------------------------------------------------------
// Product  Units Sold
// -------  ----------
// P001     19
// P002     6

if (req('data')) {
    //$date = req('date'); // Format: 2023-12-31

    $stm = $db->query(
        "SELECT u.name,COUNT(o.user_id) as count FROM orders o, user u WHERE o.user_id = u.id GROUP BY o.user_id ORDER BY count DESC LIMIT 10 ;");
    //$stm->execute([$date]);
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    json($data);
}


// Page data ----------------------------------------------


//$date = $d->max;

// ----------------------------------------------------------------------------

$_title = 'Top 10 Order By Customer';
include '../_/_head.php';
?>

<style>
    .tooltip{
        font: 16px 'Roboto';
        padding: 5px;
    }

    .tooltip img{
        border:1px solid #333;
        width:100px;
        height:100px;
        display:block;
    }

</style>



<div id="chart" style="width: 800px; height: 400px"></div>

<p>
    <!-- Testing -->
    <a href="?data=1&date=2023-12-31" target="data">Data</a>
</p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(init);

let dt, opt, cht;

function init() {
    dt = new google.visualization.DataTable();
    dt.addColumn('string', 'name');
    dt.addColumn('number', 'Count');

    const style = {
        bold: true,
        italic: false,
        fontSize: 20,
        color: 'slateblue',
    };

    opt = {
        tooltip: {
            isHtml: true,
        },

        title: 'TODO',
        fontName: 'Roboto',
        fontSize: 16,
        titleTextStyle: {
            fontSize: 20,
        },
        chartArea: {
            width: '90%',
            height: '70%',
            top: 60,
            left: 100,
        },
        legend: 'none',
        vAxis: {
            title: 'Number of Orders',
            titleTextStyle: style,
        },
        hAxis: {
            title: 'Order Status',
            titleTextStyle: style,
        },
        animation: {
            duration: 500,
            startup: true,
        },
        colors: ['slateblue'],
    };

    cht = new google.visualization.ColumnChart($('#chart')[0]);

    updateChart();
}

// #date = change event
function updateChart() {
        $.getJSON('?data=1', function(data) {
            opt.title = 'Top 10 Order By Customer';

            dt.removeRows(0, dt.getNumberOfRows());

            // Assuming 'data' is an array
            data.forEach(function(row) {
                dt.addRow([row.name, row.count]);
            });

            console.log('Drawing chart with DataTable:', dt.toJSON()); // Debugging statement

            cht.draw(dt, opt);
        });
    }
</script>

<?php
include '../_/_foot.php';