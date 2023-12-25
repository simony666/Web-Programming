<?php 
    include('../_base.php'); 
    include('_head.php');

    if(is_post()){
        // Get shopping cart (reject if empty)
        $cart = get_cart();
    
        // Retrieve total using the temp function
        $total = temp('cart_total');

    }else{
        redirect('index.php');
    }    
?>

<!-- Checkout -->
<section class="my-5 py-5">
    <div class="container text center mt-3 pt-3">
        <h2 class="form-weight-bold">Check Out</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="checkout-form" action="place_order.php" method="post">
            <div class="form-group checkout-small-element">
                <label for="">Name</label>
                <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required>
            </div>

            <div class="form-group checkout-small-element">
                <label for="">Email</label>
                <input type="text" class="form-control" id="checkout-email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group checkout-small-element">
                <label for="">Phone</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Phone" required>
            </div>

            <div class="form-group checkout-small-element">
                <label for="">City</label>
                <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required>
            </div>

            <div class="form-group checkout-large-element">
                <label for="">Address</label>
                <input type="text" class="form-control" id="checkout-address" name="adress" placeholder="Adress" required>
            </div>

            <div class="form-group checkout-btn-container cart-total">
                <p>Total amount: RM <?= $total ?></p>
                <input type="submit" value="Place Order" id="checkout-btn" class="btn" name="place_order"/>
            </div>
            <?= temp('cart_total', $total);  ?>
            

        </form>
    </div>
</section>








<?php include'_foot.php'?>