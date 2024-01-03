<?php
include '../_/_base.php';

// ----------------------------------------------------------------------------

auth('Admin');

if (is_post()) {
    $id = req('id');

    // (1) Cannot delete user #1
    if ($id == 1) {
        temp('info', 'Cannot delete record');
        redirect('../index.php');
    }

    // (2) Delete photo
    $stm = $db->prepare('SELECT photo FROM profile_pic WHERE id = ?');
    $stm->execute([$id]);
    foreach($stm->fetchAll() as $photo){
        unlink("/_/photos/profile/$photo->photo");
    }
    

    // (3) Delete user
    $stm = $db->prepare('DELETE FROM profile_pic WHERE id = ?');
    $stm->execute([$id]);
    $stm = $db->prepare('DELETE FROM user WHERE id = ?');
    $stm->execute([$id]);
    foreach($stm->fetchAll() as $photo){
        unlink("../_/photos/profile/$photo->photo");
    }
    

    temp('info', 'Record deleted');

    // (4) Logout if own's record deleted
    if ($id == $user->id) {
        logout();
    }
}

redirect('../index.php');

// ----------------------------------------------------------------------------
