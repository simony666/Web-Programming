<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {
    $email = req('email');    
    // Send activate token (if valid)
    if (!$err) {
        // TODO: (1) Select user
        $stm = $db->prepare('SELECT * FROM user WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch();
        if ($u->status == "ACTIVE") redirect("/");
        $u = get_user($u->id);

        // TODO: (2) Generate token id
        $id = sha1(rand());

        // TODO: (3) Delete and insert token
        $stm = $db->prepare('
            DELETE FROM activate_token WHERE user_id = ?;

            INSERT INTO activate_token (token, expired, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?);
        ');   
        $stm->execute([$u->id, $id, $u->id]); 

        // TODO: (4) General token url
        $url = base("user/activate_token.php?id=$id");
        echo $url;

        // TODO: (5) Send email
        $photo = $u->photos[0];
        $m = get_mail();
        $m->addAddress($u->email, $u->name);
        $m->addEmbeddedImage("../_/photos/$photo", 'photo');
        $m->isHTML(true);
        $m->Subject = 'Activate Account';
        $m->Body = "
            <img src='cid:photo' style='width: 200px; height: 200px; border: 1px solid #333'>
            <p>Dear $u->name,<p>
            <h1 style='color: red'>Account Activation</h1>
            <p>
                Please click <a href='$url'>here</a> to activate your account.
            </p>
            <p>From, 🐱 Admin</p>
        ";
        $m->send();
            

        temp('info', 'Email sent');
        redirect('/login.php');

    }
}

// ----------------------------------------------------------------------------

