<?php
    include('../_base.php');
    
    if ( req('update_status_btn') || req('update_order_btn') && req('order_id')) {
        // order_id get from orderlist 
        $order_id = post("order_id");
    
        // Get the new status from the form (update status)
        $newStatus = req('order_status');

        // Update the order status in the database
        $stm = $db->prepare(
            "UPDATE orders
            SET order_status = ?
            WHERE order_id = ?"
        );

        $order_status = $stm->execute([$newStatus, $order_id]);
    
        // Fetch delivery details
        $stm = $db->prepare(
            "SELECT u.*, s.*
            FROM user u, shipping_address s 
            WHERE s.user_id = u.id
            AND s.order_id = ?;"
        );
    
        $stm->execute([$order_id]);
        $delivery_details = $stm->fetchAll();
    
        
        // order details products part
        $stm = $db->prepare(
            "SELECT oi.*,o.* 
            FROM order_items oi, orders o 
            WHERE o.order_id = oi.order_id 
            AND o.order_id = ?;
            ");

        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();
    
        
    } else {
        echo "Orders not found!";
    }
  

    include('../_/adminLayout/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="text-white fs-4">View Order Details</span>
                    <a href="all_orderlist.php" class="btn-warning float-end"><i class="fa fa-reply pe-2"></i>Back</a>
                </div>
                <div class="card-body d-flex">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Delivery Details</h4>
                        </div>
                        <hr>
                        <?php  foreach ($delivery_details as $d): ?>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">Name</label>
                                <div class="border p-1">
                                    <?= $d->name ?>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">Email</label>
                                <div class="border p-1">
                                    <?= $d->email ?>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">Address</label>
                                <div class="border p-1">
                                    <?= $d->address ?>    
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">State</label>
                                <div class="border p-1">
                                    <?= $d->state ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">Postal Code</label>
                                <div class="border p-1">
                                    <?= $d->postal ?>
                                </div>
                            </div>
                        </div>  
                    <?php endforeach;?>  
                    </div>
                    <div class="col-md-6">
                        <h4>Order Details</h4>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($order_details as $o){ 
                                    $p = get_product($o->product_id);
                                    ?>
                                    <tr>
                                        <td class="align-middle" style="text-wrap:wrap;">
                                            <img src="../_/photos/products/<?= $p->photo[0]?>" alt="">
                                            <?= $p->product_name ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?= $p->product_price ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?= $o->unit ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <hr>
                        <h5>
                            <?php  $defaultStatus = post("order_status") ?>
                                Total Price:
                                <span class="float-end fw-bold">RM<?=  sprintf('%.2f',$o->total_cost) ?></span>
                            
                        </h5>

                        <hr>
                        
                        <label class="fw-bold">Status</label>
                        <div class="mb-3">
                            
                            <form method="post">
                                <?= hidden('order_id', 
                                $order_id)?>
                                
                                <?= selectStatus('order_status',$_orderStatus, $defaultStatus, false)?>
                                
                                <input type="submit" name="update_status_btn" class="btn btn-primary mt-2" value="Update Status" />
                            </form>
                        
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function disableOptions(selectElement) {
            var selectedValue = selectElement.value;
            var options = selectElement.options;

            for (var i = 0; i < options.length; i++) {
                if (selectedValue >= 2 && options[i].value < 2) {
                    options[i].disabled = true;
                } else {
                    options[i].disabled = false;
                }
            }
        }


</script>

<?php 
    include('../_/adminLayout/footer.php')
?>