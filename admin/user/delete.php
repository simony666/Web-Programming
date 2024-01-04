<?php
include '../../_/_base.php';
auth('Admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('id');

    $stm_pic = $db->prepare('DELETE FROM profile_pic WHERE id = ?');
    $stm = $db->prepare('DELETE FROM user WHERE id = ?');
    
    try{
        $stm_pic->execute([$id]);
        $stm->execute([$id]);
        temp('info', 'Record deleted');

    }
    catch(Exception){
        temp('info', 'Cannot delete record');
    }
}

redirect('index.php');

// ----------------------------------------------------------------------------
