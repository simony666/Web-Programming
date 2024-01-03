<?php
include '_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');
    $password = req('password');

    // Input: email
    if (!$email) {
        $err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $err['email'] = 'Invalid email';
    }

    // Input: password
    if (!$password) {
        $err['password'] = 'Required';
    }

    // Login user
    if (!$err) {
        $stm = $db->prepare('SELECT * FROM user WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch();
        
        $stm = $db->prepare("INSERT INTO `login` (`user_id`, `datetime`, `status`, `ipv4`) VALUES (?,now(), ?,?)");

        if($u->login >= $s_login_attempt){
            temp('info', 'Account Locked, Please Contact Admin To Unlock Your Account');
        }else if (sha1($password) == $u->password && $u->status == "ACTIVE") {
            $stm->execute([$u->id,"SUCCESS",$_SERVER['REMOTE_ADDR']]);
            $stm = $db->prepare("UPDATE `user` SET `login`=0 WHERE id = ?");
            $stm->execute([$u->id]);
            temp('info', 'Login successfully');
            login($u);
        }else if (sha1($password) == $u->password && $u->status == "INACTIVE"){
            $stm->execute([$u->id,"ACTIVATING",$_SERVER['REMOTE_ADDR']]);
            $stm = $db->prepare("UPDATE `user` SET `login`=0 WHERE id = ?");
            $stm->execute([$u->id]);
            temp('info', 'Please Activate Your Account');
        }
        else {
            $stm->execute([$u->id,"FAILED",$_SERVER['REMOTE_ADDR']]);
            $stm = $db->prepare("UPDATE `user` SET `login`=`login` +1 WHERE id = ?");
            $stm->execute([$u->id]);
            $left = $s_login_attempt - $u->login - 1;
            $err['password'] = "Not matched, $left attempt remaining";
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';
include '_head.php';
?>

<form method="post" class="form">
    <label for="email">Email</label>
    <?= text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="password">Password</label>
    <?= password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <section>
        <button>Login</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '_foot.php';