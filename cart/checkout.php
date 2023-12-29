<?php 
    include('../_base.php'); 
    include('../_/customerLayout/_head.php');

    // developer page -> bussiness name -> account
    if (is_post()) {
        //$order_status = get('order_status') ?? '';
    
        // Get shopping cart (reject if empty)
        $cart = get_cart();
       
        if (!$cart){
            redirect('cart.php');   
        }

        // Prepared statement to select a product by id
        $stm = $db->prepare(
            'SELECT * FROM products WHERE product_id = ?');
           
        //  Create line items
        // =======================
        // - price_data
        //     - product_data
        //         - name
        //     - currency (will in cents)
        //     - unit_amount
        // - quantity

        // Array [key|value]
        // [P001|2]

        $line_items = [];

        foreach($cart as $product_id => $unit){
            $stm->execute([$product_id]);
            $p = $stm->fetch();
        
            $line_items[] = [
                'price_data' => [
                    'product_data' => [
                        'name' => "$p->product_id | $p->product_name",
                    ],
                    'currency' => 'myr',
                    'unit_amount' => $p->product_price * 100,
                ],
                'quantity' => $unit,
            ];
        }
    
        //  Create stripe checkout session
        // ====================================
        // - mode
        // - success_url
        // - cancel_url
        // - line_items
        // - metadata (optional) <-- shopping cart
        // - client_reference_id (optional) <-- user id
        // - customer_email (optional) <-- user email

        $stripe = get_stripe();

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => base('order/place_order.php?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => base('cart/cancel.php'),
            'line_items' => $line_items,
            'metadata' => $cart,
            // 'client_reference_id' => $user->id,
            // 'customer_email' => $user->email,
        ]);


        // Store stripe session id as session variable for checking later
        $_SESSION['session_id'] = $session->id;

        // Redirect to stripe
        redirect($session->url);
    }
// Redirect to cart.php
redirect('cart.php');
?>



<?php include('../_/customerLayout/_foot.php'); ?>