<?php
include "../_base.php";
$u = $_SESSION['user'] ?? null;
$fav = req('id');

if (!$u||!$fav){
    echo "Please Login Before Add To Favourite";
}else{
    $count = $db->query("SELECT count(*) FROM favourite_products WHERE user_id = '$u->id' AND product_id = '$fav'")->fetchColumn();
    if($count<=0){
        $stm = $db->prepare('REPLACE INTO favourite_products(user_id,product_id) VALUES (?,?)');
        $stm->execute([$u->id,$fav]);
    }
    echo "true";
}

?>