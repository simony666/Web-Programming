<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// Product  Units Sold
// -------  ----------
// P001     10.00
// P002     20.00

if (req('data')) {
    $date = req('date'); // Format: 2023-12-31

    // TODO
    $stm = $db->prepare(
        "SELECT 
            product_id,
            SUM(subtotal)
        FROM 
            order_items AS i, 
            orders AS o
        WHERE 
            i.order_id = o.order_id AND
            DATE(o.order_date) = ?
        GROUP BY
            product_id
    ");
    $stm->execute([$date]);
    $data = $stm->fetchAll(PDO::FETCH_NUM);

    // Add tooltip column to data result
    $stm = $db->prepare(
        "SELECT * 
        FROM products 
        WHERE product_id = ?
    ");

    foreach($data as &$row){
        $product_id = $row[0];
        $sales = $row[1];
        
        $stm->execute([$product_id]);
        $p = $stm->fetch();
        
        //$pic = get_products($p->product_id);
        $row[] = "
            <div class='tooltip'>
                <b>$p->product_id | $p->product_name</b> <br>
                Total Sales: <b>RM $sales</b><br>
                </div>
             ";
    }


    json($data);
}

// Page data ----------------------------------------------

// TODO: Select min and max date from database
// MIN(datetime) 只会拿到 min date function
// Year 只会拿到 2023
// Month 会拿到 12
// 要 combine become 2023-12 --》 DATE_FORMAT('%Y-%m')
$d = $db->query(
    " SELECT 
        DATE_FORMAT(MIN(order_date), '%Y-%m-%d')AS min,
        DATE_FORMAT(MAX(order_date), '%Y-%m-%d') AS max
    FROM orders
")->fetch();

$date = $d->max;

// ----------------------------------------------------------------------------
$_title = 'Daily Sales by Product';
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
    <!-- TODO: Date input -->
    <?= _date('date',"min='$d->min' max='$d->max'") ?>
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
        // TODO: Add columns (Product, Units Sold)
        dt.addColumn('string','Products');
        dt.addColumn('number','Sales');

        dt.addColumn({type: 'string', role:'tooltip', p:{html:true}});
        const style = {
            bold: true,
            italic: false,
            fontSize: 20,
            color: 'purple',
        };

        opt = {
            tooltip:{
                isHtml:true,
            },
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
                title: 'Sales',
                titleTextStyle: style,
            },
            hAxis: {
                title: 'Products',
                titleTextStyle: style,
            },
            animation: {
                duration: 500,
                startup: true,
            },
            colors: ['purple'],
        };

        cht = new google.visualization.ColumnChart($('#chart')[0]);

        $('#date').change();
    }

    // #date = change event
    $('#date').change(e => {
        e.preventDefault();

        // TODO: Input range (min <--> max)
        // e.targe = #date
        const el = e.target; 
        if(el.value < el.min || el.value > el.max){
            el.value = el.max;
        }

        const param = { 
            date: $('#date').val(),
        };

        $.getJSON('?data=1', param, data => {
            opt.title = 'Daily Sales by Product - ' + param.date;

            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);

            cht.draw(dt, opt);
        });
    });
</script>

<?php
include '../_foot.php';