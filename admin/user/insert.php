<?php
include '../../_/_base.php';
auth('Admin');

// ----------------------------------------------------------------------------

if (is_get()) {

}

if (is_post()) {
    $email = req('email');
    $password = req('password');
    $confirm = req('confirm');
    $name = req('name');
    $gender = req('gender');
    $f = get_file('photo');

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
        $photo = save_photo($f,'_/photos/profile');
        
        $stm = $db->prepare('INSERT INTO user (email,password,name,role,gender,status) VALUES (?,SHA1(?),?,\'Admin\',?,"Active")');
        $stm->execute([$email,$password,$name,$gender]);
        $userID = $db->lastInsertId();
        $stm = $db->prepare("INSERT INTO profile_pic (id,photo) VALUES ($userID,?)");
        $stm->execute([$photo]);            


        
        redirect("./index.php");
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Insert';
include('../../_/layout/admin/header.php');
?>

<p>
    <button data-get="./index.php">Index</button>
</p>

<div class="form-group my-5 py-5">
    <div class="container mt-5 py-5">

    
<form method="post" class="form" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="email" class="col-sm-1 col-form-label">Email</label>
                <?= text('email', 'class="form-control col" maxlength="100"') ?>
                <?= err('email') ?>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-1 col-form-label">Password</label>
                <?= password('password', 'class="form-control col mt-2" maxlength="100"') ?>
                <?= err('password') ?>
            </div>

            <div class="form-group row">
                <label for="confirm" class="col-sm-1 col-form-label">Confirm</label>
                <?= password('confirm', 'class="form-control col mt-2" maxlength="100"') ?>
                <?= err('confirm') ?>
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-1 col-form-label">Name</label>
                <?= text('name', 'class="form-control col mt-2" maxlength="100"') ?>
                <?= err('name') ?>
            </div>
            
            <div class="form-group row">
                <label for="gender" class="col-sm-1 col-form-label">Gender</label>
                <div class="col-sm-10">
                    <div class="form-check mt-2">
                        <input type="radio" name="gender" value="Male" class="form-check-input" required> Male
                    </div>
                    <div class="form-check">
                        <input type="radio" name="gender" value="Female" class="form-check-input" required> Female
                    </div>
                </div>
                <?= err('gender') ?>
            </div>
            
            <div class="form-group">
                <label for="photo">Photo</label>
                <label class="upload">
                    <?= _file('photo', 'image/*') ?>
                    <img src="/_/images/photo.jpg" class="col m-5">
                </label>
                <?= err('photo') ?>
            </div>

            <section class="mt-2">
                <button>Submit</button>
                <button type="reset">Reset</button>
            </section>
        </form>
        </div>
</div>
<?php
include('../../_/layout/admin/footer.php');