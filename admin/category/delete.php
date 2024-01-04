<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('id');

    $stm = $db->prepare('DELETE FROM categories WHERE category_id = ?');
    
    try{
        $stm->execute([$id]);
        temp('info', 'Record deleted');

    }
    catch(Exception){
        temp('info', 'Cannot delete record');
    }
}

redirect('index.php');

// ----------------------------------------------------------------------------
