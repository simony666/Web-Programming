<?php 
    include('../_/_base.php');
    include('../_/layout/customer/_head.php');

    // user account
    //auth("Member");

    $user_id = $user->id;
    
    // get orders
    // $stm = $db->prepare(
    //     "SELECT *
    //     FROM orders 
    //     WHERE user_id = ?
    //     ORDER BY order_id DESC
    //     ");
    // $stm->execute([$user_id]);

    // $orders = $stm->fetchAll();

    // Paging (class)
    $page = req('page',1);
    $page = max($page,1);
    
    require_once '../_/lib/Pager.php';
    $p = new Pager('SELECT *
                FROM orders 
                WHERE user_id = ?
                ORDER BY order_id DESC',
                ["$user_id"], 15, $page);
    $arr = $p->result;
?>

<!-- Orders -->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5" id="target">
        <p>
            <?= $p->count ?> of <?= $p->item_count ?> record(s) | Page <?= $p->page ?> of <?= $p->page_count ?>
        </p>
        <tr>
            <th>Order id</th>
            <th>Order costs</th>
            <th>Order status</th>
            <th>Order Date</th>
            <th>Order Details</th> 
        </tr>
        <?php  foreach ($arr as $o): ?>
            <tr>
                <td>
                    <span><?= $o->order_id  ?></span>
                </td>
                <td>
                    <span><?= $o->total_cost ?></span>
                </td>
                <td>
                    <span><?= $_orderStatus[$o->order_status] ?></span>
                </td>
                <td>
                    <span><?= $o->order_date ?></span>
                </td>
                <td>
                    <form action="order_details.php" method="post">
                        <?= hidden('order_status',$o->order_status); ?>
                        <?= hidden('order_id',$o->order_id); ?>
                        <input type="submit" class="btn order-details-btn" value="details" name="order_details_btn">
                    </form>
                </td>   
            </tr>
        <?php endforeach; ?>
    </table>
    </section>
        <nav aria-label="Page navigation example">
            <?= $p->html() ?>
        </nav>
    <script>

    // TODO: AJAX
    $(document).on('click','.pager a', e=>{
        e.preventDefault();
        // const param = $(e.target).serializeArray();
        // console.log(param);
        $('#target').load(e.target.href + ' #target >');
    });
</script>
<?php include('../liveChat.php');?>
<?php include('../_/layout/customer/_foot.php');?>