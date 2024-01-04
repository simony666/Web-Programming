<?php
include '../_/_base.php';

// ----------------------------------------------------------------------------

// TODO
$a = new DateTime('-30 day');
$b = new DateTime();

$a = $a->format('Y-m-d'); //2023-12-25
$b = $b->format('Y-m-d');


// $stm = $db->prepare(
//     "SELECT
//         p.id,
//         p.name,
//         p.photo,
//         SUM(i.unit) AS unit
//      FROM
//         product AS p,
//         item AS i,
//         `order` AS o
//     WHERE
//         p.id = i.product_id AND
//         i.order_id = o.id AND
//         DATE(o.datetime) >= ? AND
//         DATE(o.datetime) <= ?
//     GROUP BY
//         p.id
//      ORDER BY unit DESC
//      LIMIT 3
// "
// );
// $stm->execute([$a, $b]);

// $arr = $stm->fetchAll();

$stm = $db->prepare(
    "SELECT p.product_id, p.product_name, COUNT(i.product_id) AS unit
     FROM products AS p, order_items AS i, orders AS o
     WHERE p.product_id = i.product_id AND
        i.order_id = o.order_id AND
        DATE(o.order_date) >= ? AND
        DATE(o.order_date) <= ?
    GROUP BY
        p.product_id
     ORDER BY unit DESC
     LIMIT 3
     "
);
$stm->execute([$a, $b]);
$arr = array();
foreach($stm->fetchAll() as $po){
    $p = get_product($po->product_id);
    $p->unit = $po->unit;
    $arr[] = $p;
}



// $stm = $db->prepare("SELECT photo FROM product_pic WHERE id = ?");
// $stm->execute([$arr->product_id]);

// ----------------------------------------------------------------------------

$_title = 'Top 3 Sales in Our Shop';
include '../_head.php';
?>

<style>
    .products {
        display: flex;
        gap: 10px;
    }

    .product img {
        border: 1px solid #333;
        width: 200px;
        height: 200px;
    }

    .product div {
        text-align: center;
    }
</style>

<div class="products">
    <?php
    $n = 1;
    foreach ($arr as $p) {
        echo "
            <div class='product'>
                <img src = '../_/photos/products/{$p->photos[0]}' >
                <div>
                    #$n = $p->product_id | $p->product_name<br>
                    $p->unit units sold
                </div>
            </div>
        ";
        $n++;
    }
    ?>
</div>

<?php
include '../_/_foot.php';
