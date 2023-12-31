<?php
include '../../_/_base.php';
auth('Admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('product_id');

    $p = get_product($id);

    foreach ($p->photos as $photo) {
        unlink("../../_/photos/products/$photo");
    }

    $stm = $db->prepare("DELETE FROM product_pic WHERE id = ?");
    $stm->execute([$id]);
    
    $stm = $db->prepare("DELETE FROM products WHERE product_id = ?");
    $stm->execute([$id]);

    temp('info', 'Record deleted');
}

redirect('index.php');

// ----------------------------------------------------------------------------
