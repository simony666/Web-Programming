<?php
include '../_/_base.php';

// ----------------------------------------------------------------------------

auth('Member','Admin');

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
    $nf = get_file('new_photo');

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
    if ($nf) {
        if (!str_starts_with($nf->type, 'image/')) {
            $err['photo'] = 'Must be image';
        } else if ($nf->size > 1 * 1024 * 1024) {
            $err['photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$err) {
        if ($nf) {
            //unlink("../_/photos/profile/$photo");

            $photo = uniqid() . '.jpg';
            require_once '/_/lib/SimpleImage.php';
            $img = new SimpleImage();
            $img->fromFile($nf->tmp_name)
                ->thumbnail(200, 200)
                ->toFile("../../_/photos/products/$photo", 'image/jpeg');

            $stm = $db->prepare('INSERT INTO product_pic(id,photo) VALUES (?,?)');
            $stm->execute([$id, $photo]);
        }
        
        
        // (2) Update user (email, name, photo)
        //$stm = $db->prepare('UPDATE profile_pic WHERE id = ?');
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

}
// ----------------------------------------------------------------------------

$_title = 'User | Profile';
include('../_/layout/customer/_head.php');
?>
<div class="form-group my-5 py-5">
    <section class="mt-5 pt-5 container">
    <form method="post" class="form" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="email" class="col-sm-1 col-form-label">Email</label>
            <?= text('email', 'class="form-control col" maxlength="100"') ?>
            <?= err('email') ?>
        </div>
        <div class="form-group row">
            <label for="name"  class="col-sm-1 col-form-label">Name</label>
            <?= text('name', 'class="form-control col mt-2" maxlength="100"') ?>
            <?= err('name') ?>
        </div>
        <div class="form-group">
            <label for="photo<?= $i ?>">Photo</label>
            <label class="upload">
                <?php for ($i = 1; $i <= count($u->photos); $i++) : ?>
                    <?php $photo = $u->photos[$i - 1]; ?>
                    <img src="../../_/photos/profile/<?= $photo ?>" data-dog="<?= $photo ?>" alt="Photo <?= $i ?>">

                <?php endfor; ?>
            </label>
            <?= err("photo{$i}") ?>
        </div>
        <label for="new_photo">Photo</label>
            <label class="upload">
                <?= _file('new_photo', 'image/*') ?>
                <img src="/_/images/photo.jpg">
            </label>
            <?= err('new_photo') ?>
        <section class="mt-2">
            <button>Submit</button>
            <button type="reset">Reset</button>
        </section>
    </form>
    </section>
</div>
<script>
    $('[data-dog]').click(e => {
        console.log("clicked");
        if (!confirm('Are you sure want to delete this photo?')) {
            return;
        }
        photo = e.target.dataset.dog;
        $.get("./deletepic.php?id=<?= $u->id ?>&photo=" + photo);
        e.target.remove();
    })
</script>
<?php
//include('../liveChat.php');
include('../_/layout/customer/_foot.php');