<?php
include "../_base.php";
$u = $_SESSION['user'] ?? null;
$fav = req('id');

if (!$u||!$fav){
    echo "Please Login Before Remove From Favourite";
}else{
    $count = $db->query("SELECT count(*) FROM favourite_products WHERE user_id = '$u->id' AND product_id = '$fav'")->fetchColumn();
    if($count>0){
        $stm = $db->prepare("DELETE FROM favourite_products WHERE user_id=? AND product_id=?");
        $stm->execute([$u->id,$fav]);
    }
    echo "true";
}

echo "<script>history.go(-1)></<script>>";

?>