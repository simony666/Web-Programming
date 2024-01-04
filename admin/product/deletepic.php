<?php
include '../../_/_base.php';
$id=req('id');
$photo=req('photo');
if (!$id && !$photo){
    return;
}
$stm = $db->prepare('DELETE FROM product_pic WHERE id = ? AND photo = ?');
$stm->execute([$id,$photo]);

unlink("/_/photos/$photo");

temp('info','Record Deleted');
?>