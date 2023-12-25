<?php
include('../_base.php');

    $order_status = get('order_status') ?? '';
    $total = $_GET['total'] ?? 0;


 include('_head.php');
?>

 <!-- Payment -->
<section class="my-5 py-5">
     <div class="container text center mt-3 pt-3">
         <h2 class="form-weight-bold text-center">Payment</h2>
         <hr class="mx-auto">
     </div>
     <div class="mx-auto container text-center">
         <p><?php get('order_status'); ?></p>
         <p>Total payment: RM <?= $total ?></p>
        <input type="submit" class="btn btn-primary" value="Pay Now">
    </div>
     
</section>

 <?php include '_foot.php' ?>
