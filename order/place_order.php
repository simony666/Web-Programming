<?php
    include ('../_base.php');

        // Get stripe session id from URL (reject if null) 要不然user might cheat us 开两个tab，买不同的东西，with one stripe session
        $session_id = req('session_id');
        if(!$session_id){
            redirect('/');
        }
    
    // Check stripe session id against session variable (reject if different)
    if($session_id != $_SESSION['session_id'])redirect('/');
    
    // Unset session variable // 不给人家重用session id，不付钱结账
    unset($_SESSION['session_id']);
    
    // Retrieve stripe session by id
    $stripe = get_stripe();
    $session = $stripe->checkout->sessions->retrieve($session_id);
    
    // Check stripe session status (reject if not complete)
    if($session->status != 'complete'){
        redirect('/');
    }
    

    // Get shopping cart data from stripe session metadata
    $cart = $session->metadata->toArray();
    if (!$cart) redirect('cart.php');


//=================================================
// Database operation: Add order and items
//=================================================
    // (1) Begin transaction
    // 执行 sql statement 的过程中，一个失败立即返回（不能再继续 insert）
    $db->beginTransaction();
    
    // 2) store info in database
    // hard code
    $order_status = 0;
    $user_id = $user->id;
    $order_date = date('Y-m-d H:i:s');
    
    //  DB transaction (insert orders and order_items)

    // (3) Insert order, keep order_id
    $stm = $db->prepare(
            " INSERT INTO orders
            (order_status,order_date, user_id)
            VALUES ( ?, ?, ?)
        ");
    $stm->execute([$order_status, $order_date,$user_id]);
    
    // 4) issue new order and store order info in database
    $order_id = $db->lastInsertId();


    // (5) Insert order_items    
    $stm = $db->prepare(
        "INSERT INTO order_items
        (order_id, product_id,  product_price, unit, subtotal)
        VALUES ( ?, ?, 
            (SELECT product_price FROM products
            WHERE product_id = ?),
                ?, 
            product_price * unit)"
    );
        
    foreach ($cart as $product_id => $unit) {
        $stm->execute([$order_id, $product_id, $product_id, $unit]);
    }
        
    // (6) Update orders (count and total)
    $stm = $db->prepare('
        UPDATE orders
        SET count = (
                SELECT SUM(unit)
                FROM order_items
                WHERE order_id = ?),
            total_cost = (
                SELECT SUM(subtotal)
                FROM order_items
                WHERE order_id = ?
            )
        WHERE order_id = ?
    ');
    $stm->execute([$order_id,$order_id,$order_id]);
    // (7) Commit transcation
    $db->commit();    
        

    // 8) remove everything from cart --> delay until payment is done
    set_cart();    


    // 9) inform user whether everything is fine or there is a problem
    temp('info','Checkout success');
    redirect("../cart/shippingAddress.php?order_id=$order_id");
   
?>
