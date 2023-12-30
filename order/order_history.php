<?php 
    include('../_base.php');
    include('../_/customerLayout/_head.php');

    // user account


    // get orders
    // use if statement to check is user login?

    // I hard code the user_id = 1
    $user_id = '1';
    
    $stm = $db->prepare(
        "SELECT *
        FROM orders 
        WHERE user_id = ?
        ORDER BY order_id DESC
        ");
    $stm->execute([$user_id]);

    $orders = $stm->fetchAll();
?>

<!-- Orders -->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5">
        <tr>
            <th>Order id</th>
            <th>Order costs</th>
            <th>Order status</th>
            <th>Order Date</th>
            <th>Order Details</th> 
        </tr>
        <?php  foreach ($orders as $p): ?>
            <tr>
                <td>
                    <span><?= $p->order_id  ?></span>
                </td>
                <td>
                    <span><?= $p->total_cost ?></span>
                </td>
                <td>
                    <span><?= $p->order_status ?></span>
                </td>
                <td>
                    <span><?= $p->order_date ?></span>
                </td>
                <td>
                    <form action="order_details.php" method="post">
                        <?= hidden('order_status',$p->order_status); ?>
                        <?= hidden('order_id',$p->order_id); ?>
                        <input type="submit" class="btn order-details-btn" value="details" name="order_details_btn">
                    </form>
                    
                </td>   

            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php 
    include('../_/customerLayout/_foot.php');;
?>