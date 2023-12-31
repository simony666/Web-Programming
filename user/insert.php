<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

auth('Admin');

if (is_post()) {
    $email = req('email');
    $password = req('password');
    $confirm = req('confirm');
    $name = req('name');
    $gender = req('gender');
    $f = get_file('photo');
    $gender = req('gender');

    if (!$email) {
        $err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'user', 'email')) {
        $err['email'] = 'Duplicated';
    }

    if (!$password) {
        $err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $err['password'] = 'Between 5-100 characters';
    }

    if (!$confirm) {
        $err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $err['confirm'] = 'Not matched';
    }

    if (!$name) {
        $err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $err['name'] = 'Maximum 100 characters';
    }

    if (!$f) {
        $err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $err['photo'] = 'Maximum 1MB';
    }

    if (!$err) {
            $photo = save_photo($f,'/_/photos/profile');
            
            $stm = $db->prepare('INSERT INTO user (email,password,name,role,gender) VALUES (?,SHA1(?),?,\'Member\',?)');
            $stm->execute([$email,$password,$name,$gender]);
            $userID = $db->lastInsertId();
            $stm = $db->prepare("INSERT INTO profile_pic (id,photo) VALUES ($userID,?)");
            $stm->execute([$photo]);            


            
            redirect("/user/activate.php?email=$email");
        }
}


// ----------------------------------------------------------------------------

$_title = 'User | Insert Admin';
include '/_/_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="email">Email</label>
    <?= text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="password">Password</label>
    <?= password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="confirm">Confirm</label>
    <?= password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>

    <label for="name">Name</label>
    <?= text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="gender">Gender</label>
    <div>
    <input type="radio" name="gender" value="Male" required> Male
    <input type="radio" name="gender" value="Female" required> Female
    </div>
    <?= err('gender') ?>

    <label for="photo">Photo</label>
    <label class="upload">
        <?= _file('photo', 'image/*') ?>
        <img src="/_/images/photo.jpg">
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