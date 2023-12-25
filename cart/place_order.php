<?php
    include ('../_base.php');

if (isset($_POST['place_order'])) {
    
    // 1) get user info and store it in database

        $cart = get_cart();

        $name = post('name');
        $email = post('email');
        $phone = post('phone');
        $city = post('city');
        $address = post('address');
        $order_cost = temp('cart_total');
        $order_status = "on_hold";
        $user_id = 1;
        $order_date = date('Y-m-d H:i:s');

        $stm = $db->prepare(
                " INSERT INTO orders
                (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

        // $stm->bindParam('isiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

        $stm->execute([$order_cost, $order_status, $user_id, $phone, $city, $address, $order_date]);

        $order_id = $db->lastInsertId();
        echo $order_id;


    // 2) get products from cart

    // 3) issue new order and store order info in database

    // 4) store each single item in order_items database


    // 5) remove everything from cart


    // 6) inform user whether everything is fine or there is a problem

} else {
    
}
?>
