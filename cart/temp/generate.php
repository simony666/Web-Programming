<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------

$a = new DateTime('2023-12-01');
$b = new DateTime('2023-12-31');

if (is_get()) {
    $members  = $db->query('SELECT * FROM user WHERE role = "Member"')->fetchAll();
    $products = $db->query('SELECT * FROM products')->fetchAll();

    $stm_insert_order = $db->prepare(
        'INSERT INTO orders (order_status, order_date, count,total_cost, user_id)
         VALUES (0,?, 0, 0.00, ?)
    ');

    $stm_insert_item  = $db->prepare(
        'INSERT INTO order_items (order_id, product_id, product_price, unit, subtotal)
         VALUES (?, ?, ?, ?, product_price * unit)
    ');

    $stm_update_order = $db->prepare(
        'UPDATE orders 
         SET count = (SELECT SUM(unit) FROM order_items WHERE order_id = ?), 
             total_cost= (SELECT SUM(subtotal) FROM order_items WHERE order_id = ?)
         WHERE order_id = ?
    ');

    $db->beginTransaction();

    for ($d = clone $a; $d <= $b; $d->modify('+1 day')) {
        foreach (range(1, 5) as $n) { // 10 x orders per day

            // --------------------------------------------
            // (1) Insert 1 x order
            shuffle($members);
            $m = $members[0];
            $stm_insert_order->execute([$d->format('Y-m-d'), $m->id]);
            $id = $db->lastInsertId();

            // (2) Insert 3 x items
            shuffle($products);
            foreach (range(0, 2) as $i) { // 3 x items per order
                $p = $products[$i];
                $unit = rand(1, 5); // Random 1-10 unit
                $stm_insert_item->execute([$id, $p->product_id, $p->product_price, $unit]);
            }

            // (3) Update order (count and total)
            $stm_update_order->execute([$id, $id, $id]);
            // --------------------------------------------

        }
    }

    $db->commit();

    temp('info', 'Orders generated');
}

redirect('/index.php');

// ----------------------------------------------------------------------------
