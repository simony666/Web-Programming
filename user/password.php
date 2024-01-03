<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

auth();

if (is_post()) {
    $password = req('password');
    $new_password = req('new_password');
    $confirm = req('confirm');

    // Input: password
    if (!$password) {
        $err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $err['password'] = 'Between 5-100 characters';
    }
    else {
        $stm = $db->prepare('
            SELECT COUNT(*) FROM user
            WHERE password = SHA1(?) AND id = ?
        ');
        $stm->execute([$password, $user->id]);
        
        if ($stm->fetchColumn() == 0) {
            $err['password'] = 'Not matched';
        }
    }

    // Input: new_password
    if (!$new_password) {
        $err['new_password'] = 'Required';
    }
    else if (strlen($new_password) < 5 || strlen($new_password) > 100) {
        $err['new_password'] = 'Between 5-100 characters';
    }

    // Input: confirm
    if (!$confirm) {
        $err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $new_password) {
        $err['confirm'] = 'Not matched';
    }

    // DB operation
    if (!$err) {
        // Update user (password)
        $stm = $db->prepare('
            UPDATE user
            SET password = SHA1(?)
            WHERE id = ?
        ');
        $stm->execute([$new_password, $user->id]);

        temp('info', 'Record updated');
        redirect('../');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Password';
include '/_/_head.php';
?>

<form method="post" class="form">
    <label for="password">Password</label>
    <?= password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="new_password">New Password</label>
    <?= password('new_password', 'maxlength="100"') ?>
    <?= err('new_password') ?>

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
include '/_/_foot.php';