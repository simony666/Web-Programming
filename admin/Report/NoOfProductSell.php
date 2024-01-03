<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------
// Program  Student Count
// -------  -------------
// RIT      10
// RSD      20

if (req('data')) {
    $year = req('year');

    // Temporary array [month => sales]
    $arr = [
        'Jan' => 0,
        'Feb' => 0,
        'Mar' => 0,
        'Apr' => 0,
        'May' => 0,
        'Jun' => 0,
        'Jul' => 0,
        'Aug' => 0,
        'Sep' => 0,
        'Oct' => 0,
        'Nov' => 0,
        'Dec' => 0,
    ];


    // $stm = $db->prepare(
    //     "SELECT 
    //         p.product_id,
    //         SUM(i.unit)
    //     FROM order_items AS i, products AS p
    //     WHERE i.product_id = p.product_id
    //     GROUP BY i.product_id 
    // ");

    $stm = $db->prepare(
        "SELECT 
            DATE_FORMAT(o.order_date, '%b'),
            SUM(i.units)
         FROM orders AS o, order_items AS i
         WHERE 
            o.order_id = i.order_id
            YEAR(o.order_date) = ?
         GROUP BY MONTH(o.order_date)
    ");
    $stm->execute([$year]);

    // $stm->execute();
    // $data = $stm->fetchAll(PDO::FETCH_NUM);
    
    // merge $arr array with the FETCH_KEY_PAIR replace the $arr
    $arr = array_merge($arr, $stm->fetchAll(PDO::FETCH_KEY_PAIR));
    $data = [];
    foreach($arr as $k => $v){
        $data[] = [$k,$v];
    }


    // TODO: Add tooltip column to data result
    $stm = $db->prepare(
        "SELECT * 
        FROM products 
        WHERE product_id = ?
    ");

    foreach($data as &$row){
        $product_id = $row[0];
        $unit = $row[1];
        
        $stm->execute([$product_id]);
        $p = $stm->fetch();
        
        //$pic = get_products($p->product_id);
        $row[] = "
            <div class='tooltip'>
                <b>$p->product_id | $p->product_name</b> <br>
                Units Sold: <b>$unit</b><br>
                </div>
             ";
    }

    json($data);
}

// ----------------------------------------------------------------------------

$_title = 'Demo 3 | Column Chart #1';
include '/_/_head.php';
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

<div id="chart" style="width: 600px; height: 400px"></div>

<p>
    <a href="#" id="reload">Reload</a> |
    <a href="#" id="toggle">Toggle</a>
</p>

<p>
    <!-- Testing -->
    <a href="?data=1" target="data">Data</a>
</p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;

    function init() {
        dt = new google.visualization.DataTable();
        // TODO: Columns
        dt.addColumn('string','product_name');
        dt.addColumn('number', 'unit');

        // TODO: Add tooltip column (type, role, p.html = true)
        // p = properties is a html
        dt.addColumn({type: 'string', role:'tooltip', p:{html:true}});

        // TODO: Title text style
        const style = {
            bold:true,
            italic:false,
            fontSize:20,
            color:'purple',
        };

        opt = {
            // TODO: tooltip.isHtml = true
            tooltip:{
                isHtml:true,
            },

            title: 'Daily Units Sold by Product',
            fontName: 'Roboto',
            fontSize: 16,
            titleTextStyle: { 
                fontSize: 20,
            },
            chartArea: {
                width: '80%',
                height: '70%',
                top: 60,
                left: 80,
            },
            // TODO: colors, legend, vAxis, hAxis, animation, orientation
            colors:['purple'],
            legend:'none',
            vAxis:{
                title:'Units Sold',
                titleTextStyle:style,
                minValue:0,
                maxValue:500,
            },
            hAxis:{
                title:'Products',
                titleTextStyle:style,
            },
            animation:{
                duration:500, // 500 miliseconds
                startup:true, // 动画从下面上来
            },
            orientation: 'horizontal',
        };

        // TODO: Column chart
        cht = new google.visualization.ColumnChart($('#chart')[0]);

        $('#reload').click();
    }

    $('#reload').click(e => {
        e.preventDefault();

        const param = {};

        $.getJSON('?data=1', param, data => {
            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);
            cht.draw(dt, opt);
        });
    });

    // TODO: #toggle = click event
    $('#toggle').click(e => {
        e.preventDefault();

        // Toggle orientation (horizontal <--> vertical)
        // Toggle axis (vAxis <--> hAxis)
        opt.orientation = opt.orientation == 'horizontal' ? 'vertical' : 'horizontal';

        [opt.vAxis,opt.hAxis] = [opt.hAxis,opt.vAxis]

        cht.draw(dt, opt);
    });
</script>

<?php
include '/_/_foot.php';