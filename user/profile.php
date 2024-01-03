<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

auth();

if (is_get()) {
    $u = get_user($user->id,true);

    if (!$u) {
        redirect('/');
    }

    $email = $u->email;
    $name  = $u->name;
    $photo = $u->photos[0];
}

if (is_post()) {
    $email = req('email');
    $name  = req('name');
    $photo = null;
    $f = get_file('photo');

    // Input: email
    if (!$email) {
        $err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $err['email'] = 'Invalid email';
    }
    else if ($email != $_SESSION['email'] && !is_unique($email, 'user', 'email')) {
        $err['email'] = 'Duplicated';
    }

    // Input: name
    if (!$name) {
        $err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $err['name'] = 'Maximum 100 characters';
    }

    // Input: photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $err['photo'] = 'Must be image';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $err['photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$err) {
        // (1) Delete and save photo (optional)
        if ($f && true == false) {
            unlink("../_/photos/$photo");
            $photo = save_photo($f, '../_/photos');
            }
        }
        
        
        // (2) Update user (email, name, photo)
        $stm = $db->prepare('UPDATE profile_pic WHERE id = ?');
        $stm->execute([$photo, $id]);
        $stm = $db->prepare('
            UPDATE user
            SET email = ?, name = ?
            WHERE id = ?
        ');
        $stm->execute([$email, $name, $user->id]);

        get_user($user->id,true);

        temp('info', 'Record updated');
        redirect('/');
    }


// ----------------------------------------------------------------------------

$_title = 'User | Profile';
include '../_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="email">Email</label>
    <?= text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="name">Name</label>
    <?= text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="photo">Photo</label>
    <label class="upload">
        <?= _file('photo', 'image/*') ?>
        <img src="/_/photos/<?= $photo ?>">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include('../liveChat.php');
include '/_/_foot.php';