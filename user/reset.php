<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');

    // Input: email
    if (!$email) {
        $err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $err['email'] = 'Invalid email';
    }
    else if (!is_exists($email, 'user', 'email')) {
        // TODO
        $err['email'] = 'Not exists';
    }

    // Send reset token (if valid)
    if (!$err) {
        // TODO: (1) Select user
        $stm = $db->prepare('SELECT * FROM user WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch();
        echo $u->id;

        // TODO: (2) Generate token id
        $id = sha1(rand());

        // TODO: (3) Delete and insert token
        $stm = $db->prepare('
            DELETE FROM reset_token WHERE user_id = ?;

            INSERT INTO reset_token (token, expired, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?);
        ');   
        $stm->execute([$u->id, $id, $u->id]); 

        // TODO: (4) General token url
        $url = base("user/reset_token.php?id=$id");
        echo $url;

        // TODO: (5) Send email
        $m = get_mail();
        $m->addAddress($u->email, $u->name);
        //$m->addEmbeddedImage("_/photos/profile_pic", 'photo');
        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            <img src='cid:photo' style='width: 200px; height: 200px; border: 1px solid #333'>
            <p>Dear $u->name,<p>
            <h1 style='color: red'>Reset Password</h1>
            <p>
                Please click <a href='$url'>here</a> to reset your password.
            </p>
            <p>From, 🐱 Admin</p>
        ";
        $m->send();
            

        temp('info', 'Email sent');
        redirect('../');

    }
}

// ----------------------------------------------------------------------------

$_title = 'Reset Password';
include '/_/_head.php';
?>

<form method="post" class="form">
    <label for="email">Email</label>
    <?= text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include('../liveChat.php');
include '/_/_foot.php';