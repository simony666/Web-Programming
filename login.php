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
        $stm = $db->prepare('SELECT * FROM user WHERE email = ? AND password = SHA1(?)');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u && $u->status == "ACTIVE") {
            temp('info', 'Login successfully');
            login($u);
        }else if ($u && $u->status == "INACTIVE"){
            temp('info', 'Please Activate Your Account');
        }
        else {
            $err['password'] = 'Not matched';
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