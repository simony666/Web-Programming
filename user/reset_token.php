<?php
include '../_/_base.php';

// ----------------------------------------------------------------------------

// TODO: (1) Delete expired tokens
$db->query('DELETE FROM reset_token WHERE Expired < NOW()');

// TODO: (2) Is token id valid?
$id = req('id');
if (!is_exists($id, 'reset_token', 'token')) {
    temp('info', 'Invalid token. Try again');
    redirect('../');
}


if (is_post()) {
    $password = req('password');
    $confirm = req('confirm');

    // Input: password
    if (!$password) {
        $err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $err['password'] = 'Between 5-100 characters';
    }

    // Input: confirm
    if (!$confirm) {
        $err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $err['confirm'] = 'Not matched';
    }

    // DB operation
    if (!$err) {
        // TODO: Update user (password) based on token id + delete token
        $stm = $db->prepare('
            UPDATE user 
            SET password = SHA1(?)
            WHERE id = (SELECT user_id FROM reset_token WHERE token = ?);

            DELETE FROM reset_token WHERE token =?;
        ');
        $stm->execute([$password, $id, $id]);

        temp('info', 'Record updated');
        redirect('../login.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Reset Password';
include '../_/_head.php';
?>

<form method="post" class="form">
    <label for="password">Password</label>
    <?= password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="confirm">Confirm</label>
    <?= password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include('../liveChat.php');
include '../_/_foot.php';