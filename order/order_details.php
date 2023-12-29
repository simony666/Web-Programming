<?php 

    /*Order status*/ 
    // 1) delivered
    // 2) Shipped
    // 3) Unpaid

    include('../_base.php');

    if(req('order_details_btn') && req('order_id')){
        $order_id = post("order_id");


        // order details products part
        $stm = $db->prepare(
            "SELECT o.*, p.*
            FROM order_items AS o
            JOIN products AS p 
            ON o.product_id = p.product_id
            WHERE o.order_id = ?
            ");

        $stm->execute([$order_id]);
        $product_details = $stm->fetchAll();
        
        // order details total_cost + count part
        $stm = $db->prepare(
            "SELECT total_cost, count
            FROM orders
            WHERE order_id = ?
        ");
        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();
    }else{
         redirect("order_history.php");
    }

    include('../_/customerLayout/_head.php');
?>

<!-- Orders -->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Order Details</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5 mx-auto">
        <thead>
            <th>Product</th>
            <th>Price (RM)</th>
            <th>Quantity</th>
        </thead>
        <tbody>
            <?php  foreach ($product_details as $p): ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="../_/photos/products/<?= $p->product_image ?>" alt="">
                            <div>
                                <p class="mt-3"><?= $p->product_name ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span><?= $p->product_price ?></span>
                    </td>
                    <td>
                        <span><?= $p->unit ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>     
        <tfoot>
            <?php  foreach ($order_details as $o): ?>
                <tr style="border-top:3px solid #fb774b;">
                    <td class="text-end fw-bold">Total: </td>
                    <td>RM <?= sprintf('%.2f',$o->total_cost) ?></td>
                    <td><?= $o->count ?></td>
                </tr>
            <?php endforeach; ?>
        </tfoot>
    </table>
</section>

<?php include('../_/customerLayout/_foot.php'); ?>