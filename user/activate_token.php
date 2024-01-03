<?php
include '../_base.php';

// ----------------------------------------------------------------------------



$id = req('id');
if (!is_exists($id, 'activate_token', 'token')) {
    temp('info', 'Invalid token. Try again');
    redirect('/login.php');
    return;
}
$db->query('DELETE FROM activate_token WHERE Expired < NOW()');
if (is_exists($id, 'activate_token', 'token')){
    $stm = $db->prepare("SELECT user_id FROM activate_token WHERE token = ?");
    $stm->execute([$id]);
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user_id = $row['user_id'];

        $stm = $db->prepare("UPDATE user SET status = 'ACTIVE' WHERE id = ?");
        $stm->execute([$user_id]);

        // Delete the activation token from the database
        $stm = $db->prepare("DELETE FROM activate_token WHERE token = ?");
        $stm->execute([$id]);

        temp('info', 'Email activated successfully');
    }
}

// Check if user status is active, then redirect to login page
redirect('/login.php');

