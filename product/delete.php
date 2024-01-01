<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('product_id');

    // // TODO: Delete photo
    // $stm = $db->prepare('SELECT photo FROM product WHERE id = ?');
    // $stm->execute([$id]);
    // $photo = $stm->fetchColumn();
    // unlink("../_/products/$photo");

    // $stm = $db->prepare('DELETE FROM product WHERE id = ?');
    // $stm->execute([$id]);

    $p = get_product($id);
    $stm = $db->prepare("DELETE FROM product_pic WHERE id = ?");
    $stm->execute([$id]);
    $photo = $stm->fetchAll();
    unlink("../_/photos/$photo");

    $stm = $db->prepare("DELETE FROM products WHERE product_id = ?");
    $stm->execute([$id]);

    temp('info', 'Record deleted');
}

redirect('index.php');

// ----------------------------------------------------------------------------
