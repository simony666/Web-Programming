<?php
include('../_base.php');

$order_id = req('order_id'); 
if(!$order_id){
    redirect('cart.php');
    //echo "$order_id";
}

// validation
if (post('place-order')) {
    $user_id = $user->id;
    $address = req('address');
    $state = req('state');
    $postal = req('postal');

    // Input: address
    if (!$address) {
        $err['address'] = 'Required';
    }
    
    // input: state
    if(!$state){
        $err['state'] = 'Required';
    }else if(!array_key_exists($state, $_states)){
        $err['state'] = 'Not exists';
    }

    // Input: postal code
    if (!$postal) {
        $err['postal'] = 'Required';
    }else if(!preg_match('/^[0-9]{5}$/',$postal)){
        $err['postal'] = 'Postal code only consists 5 digits';
    }

    // insert record to database
    $stm = $db->prepare(
        "REPLACE INTO shipping_address(order_id,user_id,address,state,postal)
        VALUES (?,?,?,?,?)
        ");
    $stm->execute([$order_id,$user_id,$address,$state,$postal]);
    print_r($user);
    redirect('../order/order_history.php');
}



include('../_/customerLayout/_head.php');
?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Shipping Address</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="checkout-form" method="post">
            <div class="form-group checkout-large-element">
                <label>Address <span style="color:red;">*</span></label>
                <?= text('address','class="form-control" maxlength="100" placeholder="Address" required ')?>
                <?= err('address') ?>
            </div>

            <div class="form-group checkout-small-element">
                <label>State<span style="color:red;">*</span></label>
                <?= select('state',$_states, null, false,'class="form-control" required') ?>
                <?= err('state') ?>
            </div>

            <div class="form-group checkout-small-element">
                <label>Postal Code<span style="color:red;">*</span></label>
                <?= text('postal','class="form-control" maxlength="5" pattern="\d{5}" placeholder="XXXXX" required ')?>
                <?= err('postal') ?>
            </div>

            <div class="form-group checkout-btn-container">
                <?= hidden('order_id', $order_id); ?>
                <input type="submit" value="Checkout" class="btn" id="checkout-btn" name="place-order" />
            </div>
        </form>
    </div>
</section>

<?php include('../_/customerLayout/_foot.php'); ?>
