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
        "SELECT u.name,COUNT(o.user_id) as count FROM orders o, user u WHERE o.user_id = u.id GROUP BY o.user_id ;");
    //$stm->execute([$date]);
    $data = $stm->fetch(PDO::FETCH_ASSOC);

    json($data);
}


// Page data ----------------------------------------------

$d = $db->query(
    "SELECT
        DATE(MIN(order_date)) AS min,
        DATE(MAX(order_date)) AS max
     FROM orders
")->fetch();

//$date = $d->max;

// ----------------------------------------------------------------------------

$_title = 'Demo 10 | Column Chart #7';
include '../_head.php';
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

<p>
    <?= _date('date', "min='$d->min' max='$d->max'") ?>
</p>

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
    dt.addColumn('string', 'Order Status');
    dt.addColumn('number', 'Number of Orders');

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

    $('#date').change();
}

// #date = change event
$('#date').change(e => {
    e.preventDefault();

    const el = e.target;
    if (el.value < el.min || el.value > el.max) {
        el.value = el.max;
        return;
    }

    const param = { 
        date: $('#date').val(),
    };

    $.getJSON('?data=1', param, data => {

        opt.title = 'Daily Order Status - ' + param.date;

        dt.removeRows(0, dt.getNumberOfRows());

        // Add rows to DataTable
        dt.addRow(['Pending', data.pending]);
        dt.addRow(['Preparing', data.preparing]);
        dt.addRow(['Completed', data.completed]);

        console.log('Drawing chart with DataTable:', dt.toJSON()); // Debugging statement

        cht.draw(dt, opt);
    });
});

        
</script>

<?php
include '../_foot.php';