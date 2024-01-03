<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// Day         Sales
// ----------  -----
// 2023-12-01  10.00
// 2023-12-02  20.00

// if req = 1 = true
if (req('data')) {
    $month = req('month'); // Format: 2023-12

    // TODO
    $stm = $db->prepare(
        "SELECT 
            order_date,
            SUM(total_cost)
        FROM orders
        WHERE DATE_FORMAT(order_date, '%Y-%m') = ?
        GROUP BY order_date
    ");
    $stm->execute([$month]);
    $data = $stm->fetchAll(PDO::FETCH_NUM);

    json($data);
}

// Page data ----------------------------------------------

// Select min and max month from database
$m = $db->query(
    " SELECT 
        DATE_FORMAT(MIN(order_date), '%Y-%m' )AS min,
        DATE_FORMAT(MAX(order_date), '%Y-%m' )AS max
    FROM orders
")->fetch();

$month = $m->max;

// ----------------------------------------------------------------------------

$_title = 'Overall Sales by Month';
include '../_head.php';
?>

<p>
    <!-- TODO: Month input -->
    <?= month('month',"min='$m->min' max='$m->max'"); ?>
</p>

<div id="chart" style="width: 800px; height: 400px"></div>

<p>
    <!-- Testing -->
    <a href="?data=1&month=2023-12" target="data">Data</a>
</p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;

    function init() {
        dt = new google.visualization.DataTable();
        // TODO: Add columns (Day, Sales)
        dt.addColumn('date','Day');
        dt.addColumn('number','Sales');

        const style = {
            bold: true,
            italic: false,
            fontSize: 20,
            color: 'tomato',
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
                title: 'Day',
                titleTextStyle: style,
                // TODO: maxTextLines, format
                maxTextLines:1,
                // change the day format
                format:'dd',
            },
            animation: {
                duration: 500,
                startup: true,
            },
            colors: ['tomato'],
            // 十字线: crosshair (trigger, orientation, color, opacity)
            crosshair:{
                trigger:'focus', // both, selection
                orientation:'both', // vertical, horizontal
                color: 'black',
                opacity:1,
            }
        };

        // Line chart
        cht = new google.visualization.LineChart($('#chart')[0]);

        $('#month').change();
    }

    // #month = change event
    $('#month').change(e => {
        e.preventDefault();

        // Limit what user can do: Input range (min <--> max)
        // e.targe = #month
        const el = e.target; 
        if(el.value < el.min || el.value > el.max){
            el.value = el.max;
        }
        const param = { 
            month: $('#month').val(),
        };

        $.getJSON('?data=1', param, data => {
            // TODO: Update title --> December 2023
            const d = new Date(param.month);
            const m = d.toLocaleString('en', {month: 'long', year: 'numeric'});
            opt.title = 'Overall Sales by Day - ' + m;

            // TODO: Column 0 --> date
            for(const row of data){
                // index 0 = row 0
                row[0] = new Date(row[0]);
            }

            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);

            // TODO: Format day (column 0) --> tooltip
            new google.visualization
                    // format to: 16 December 2023
                      .DateFormat({pattern:'dd MMMM yyyy'}) 
                      .format(dt,0);

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