<?php
include "./_/_base.php";

$arr = $db->query("SELECT order_id,user_id FROM orders")->fetchAll();

foreach($arr as $a){
    $postal = random_int(10000,99999);
    $stm = $db->prepare('REPLACE INTO shipping_address(order_id,user_id,address,state,postal) VALUES (?,?,?,?,?)');
    $stm->execute([$a->order_id,$a->user_id,"123,abc abc","KUL",$postal]);
}

?>