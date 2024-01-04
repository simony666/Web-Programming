<?php 
    include('../../_/_base.php');

    if(is_post()){
        $order_id = post("order_id");
        $user_id = $user->id;

        // order details 
        $stm = $db->prepare(
            "SELECT oi.*,o.*,
            DATE_FORMAT(o.order_date, '%d-%m-%Y') AS order_date
            FROM order_items oi, orders o 
            WHERE o.order_id = oi.order_id 
            AND o.order_id = ?;
        ");

        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();


        // get shipping address
        $stm = $db->prepare(
            "SELECT s.*, u.name
            FROM shipping_address AS s, user AS u
            WHERE s.user_id = u.id 
            AND s.order_id = ?
        ");

        $stm->execute([$order_id]);
        $user_details = $stm->fetch();

        $stm->execute([$order_id]); 
        $addr = $stm->fetchAll();

        $user_name = $user_details->name;

    }else{
        redirect("../order_history.php");
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
</head>
<body>
<div >
    <div style="text-align: center;">
        <div style="font-size: 24px;color: #666;">RECEIPT</div>
        <hr>
    </div>

    <?php  foreach ($order_details as $o){ 
        $p = get_product($o->product_id);
        ?>
    <table style="line-height: 1.5; margin:0 auto; width: 80%;" class="mt-5 pt-5">
        <tr>
            <td><b>Order id:</b> 
               <?= $o->order_id ?>
            </td>
            
            <td style="text-align:right;">
                <b>Receiver Address:</b>
            </td>
        </tr>
        <tr>
            <td>
                <b>Order Date:</b> 
                <?= $o->order_date ?>
            </td>
            <?php  foreach ($addr as $a): ?>
                <td style="text-align:right;float:right;">
                    <?= $a->address; ?><br>
                    <?= $a->postal ?>
                    <?= $_states[$a->state] ?>
                </td>
             <?php endforeach;?>
        </tr>
        <tr>
            <td>   
                <b>Name: </b>
                <?= $user_name?>
            </td>
        </tr>
    </table>

    <table style="line-height: 2; margin:0 auto; width: 80%;">
            <tr style="font-weight: bold;border:1px solid #cccccc;background-color:#f2f2f2;">
                <td style="border:1px solid #cccccc;width:200px;">Product Name</td>
                <td style = "text-align:center;border:1px solid #cccccc;width:100px">Price (RM)</td>
                <td style = "text-align:center;border:1px solid #cccccc;width:100px;">Quantity</td>
                <td style = "text-align:center;border:1px solid #cccccc;">Subtotal (RM)</td>
            </tr>
            
            <tr> 
                <td style="border:1px solid #cccccc;">
                    <?= $p->product_name; ?>
                </td>
                <td style = "text-align:center; border:1px solid #cccccc;">
                    <?= $p->product_price; ?>
                </td>
                <td style = "text-align:center; border:1px solid #cccccc;">
                    <?= $o->unit ?>
                </td>
                <td style = "text-align:center; border:1px solid #cccccc;">
                    <?= $o->subtotal; ?>
                </td>
            </tr>
            <tr style = "font-weight: bold;">
                <td></td><td></td>
                <td style = "text-align:center;">Total (RM):</td>
                <td style = "text-align:center;"><?= $o->total_cost; ?></td>
            </tr>
    <?php } ?>
</table>
<hr>
<form method="post">
    <input type="submit" class="btn print-btn" value="Print" name="print_btn" id="print" style="margin: 0 auto; display: block; padding:5px;">
</form>
</div>    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#print').click(function (e) {
            e.preventDefault();
            var printWindow = window.open('', '_blank');
            printWindow.document.write($('html').html());
            printWindow.document.close();
            printWindow.print();
        });
    });
</script>
</body>
<?php include('../../liveChat.php');?>
</html>