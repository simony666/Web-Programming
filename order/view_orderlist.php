<?php
    include('../_base.php');
    
    if(req('update_order_btn') && req('order_id')){
        $order_id = post("order_id");
        

        // delivery details
        $stm = $db->prepare(
            "SELECT u.*, s.*
            FROM user AS u
            JOIN shipping_address AS s 
            ON s.user_id = u.id
        ");

        $stm->execute();
        $delivery_details = $stm->fetchAll();

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
        
        // order details total_cost
        $stm = $db->prepare(
            "SELECT total_cost
            FROM orders
            WHERE order_id = ?
        ");
        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();
    }else{
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
                                <?php  foreach ($product_details as $p): ?>
                                    <tr>
                                        <td class="align-middle">
                                            <img src="../_/photos/products/<?= $p->photo[0]?>" alt="">
                                            <?= $p->product_name ?>
                                        </td>
                                        <td class="align-middle text-center">
                                        <?= $p->product_price ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?= $p->unit ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <hr>
                        <h5>
                            <?php  foreach ($order_details as $o): ?>
                                Total Price:
                                <span class="float-end fw-bold">RM<?=  sprintf('%.2f',$o->total_cost) ?></span>
                            <?php endforeach?>
                        </h5>

                        <hr>

                        <label class="fw-bold">Status</label>
                        <div class="mb-3">
                            <form method="post">
                                <select name="order_status" id="" class="form-select ps-2">
                                    <option value="0">Pending</option>
                                    <option value="1">Preparing</option>
                                    <option value="2">Completed</option>
                                    <option value="3">Cancelled</option>
                                </select>
                                <button type="submit" name="update_order_btn" class="btn btn-primary mt-2">Update Status</button>
                            </form>
                            
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include('../_/adminLayout/footer.php')
?>