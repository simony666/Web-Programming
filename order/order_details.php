<?php 

    include('../_base.php');

    if(req('order_details_btn') && req('order_id')){
        $order_id = post("order_id");
        

        // order details products part
        $stm = $db->prepare(
            "SELECT oi.*,o.* 
            FROM order_items oi, orders o 
            WHERE o.order_id = oi.order_id 
            AND o.order_id = ?;
            ");

        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();
        
        // get shipping address
        $stm = $db->prepare(
            "SELECT address, postal, state
            FROM shipping_address
            WHERE order_id = ?
            ");

        $stm->execute([$order_id]);
        $addr = $stm->fetch();
        $address = $addr->address.", ".$addr->postal." ".$_states[$addr->state];

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
    <div class="row ">
        <table class="mt-5 pt-5  col">
            <tr>
                <th class="text-center">Shipping address</th>
                <td class="text-center"><?= $address?></td>
            </tr>
        </table>
        <div class="col"></div>
    </div>
    <table class="mt-5 pt-5 mx-auto">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php  foreach ($order_details as $o){ 
                    $p = get_product($o->product_id);
                ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="../_/photos/products/<?= $p->photos[0] ?>" alt="">
                            <div>
                                <p class="mt-3"><?= $p->product_name ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span><?= $p->product_price ?></span>
                    </td>
                    <td>
                        <span><?= $o->unit ?></span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>     
        <tfoot>
            <tr style="border-top:3px solid #fb774b;">
                <td class="text-end fw-bold">Total: </td>
                <td>RM <?= sprintf('%.2f',$o->total_cost) ?>
            </td>
                <td><?= $o->count ?></td>
            </tr>
        </tfoot>
    </table>
    <form action="../order/receipt/e-receipt.php" method="post" class="float-end">
        <?= hidden('order_id',$o->order_id); ?>
        <input type="submit" class="btn order-details-btn" value="View Receipt" name="receipt_btn">
    </form>
</section>



<?php include('../_/customerLayout/_foot.php'); ?>