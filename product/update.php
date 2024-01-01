<?php
include '../_base.php';

// ----------------------------------------------------------------------------
$photos = [];
if (is_get()) {
    $id = req('product_id');

    $p = get_product($id);

    if (!$p) {
        redirect('index.php');
    }

    $name = $p->product_name;
    $price = $p->product_price;
    $desc = $p->product_desc;
    //$photo = $_SESSION['photo'] = $p->photo; // TODO
    $photo = $p->photos[0];
    $category_id = $p->category_id;
}

if (is_post()) {
    $id = req('product_id');
    $name = req('name');
    $desc = req('desc');
    $price = req('price');
    // $photo = $_SESSION['photo'];
    $category_id = req('category_id');

    $f = get_file('photo');


    // Input: name
    if (!$name) {
        $err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $err['name'] = 'Maximum 100 characters';
    }

    // Input: price
    if ($price == '') {
        $err['price'] = 'Required';
    } else if (!is_money($price)) {
        $err['price'] = 'Must be money';
    } else if ($price < 0.01 || $price > 9999.99) {
        $err['price'] = 'Must between 0.01 - 9999.99';
    }

    // Input: photo (if not null)
    // if ($f) {
    //     if (!str_starts_with($f->type, 'image/')) {
    //         $err['photo'] = 'Must be image';
    //     } else if ($f->size > 1 * 1024 * 1024) {
    //         $err['photo'] = 'Maximum 1MB';
    //     }
    // }
    $newPhotos = [];
    for ($i = 1; $i <= 4; $i++) {
        $fileKey = "photo$i";
        $f = get_file($fileKey);

        if ($f) {
            if (!str_starts_with($f->type, 'image/')) {
                $err[$fileKey] = 'Must be an image';
            } elseif ($f->size > 1 * 1024 * 1024) {
                $err[$fileKey] = 'Maximum 1MB';
            } else {
                $newPhoto = uniqid() . '.jpg';
                require_once '../lib/SimpleImage.php';
                $img = new SimpleImage();
                $img->fromFile($f->tmp_name)
                    ->thumbnail(200, 200)
                    ->toFile("../_/photos/$newPhoto", 'image/jpeg');

                $newPhotos[] = $newPhoto;
            }
        }
    }


    // Input: category_id
    if (!$category_id) {
        $err['category_id'] = 'Required';
    } else if (!array_key_exists($category_id, $_categories)) {
        $err['category_id'] = 'Not exists';
    }

    // DB operation
    if (!$err) {
        // TODO: Delete photo + save photo (if not null)
        if ($f) {
            //unlink("../_/photos/$photo");

            $photo = uniqid() . '.jpg';
            require_once '../lib/SimpleImage.php';
            $img = new SimpleImage();
            $img->fromFile($f->tmp_name)
                ->thumbnail(200, 200)
                ->toFile("../_/photos/$photo", 'image/jpeg');
        }


        $stm = $db->prepare('
            UPDATE products
            SET product_name = ?, product_desc = ?, product_price = ?, category_id = ?
            WHERE product_id = ?
        ');
        $stm->execute([$name, $desc, $price, $category_id, $id]);

        $stm = $db->prepare('UPDATE product_pic SET photo = ? WHERE id = ?');
        foreach ($newPhotos as $i => $newPhoto) {
            //$stm->execute([$newPhoto, $id * 4 + $i + 1]); ////////////////////////////////TODO
        }


        temp('info', 'Record updated');
        redirect('index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Update';
include '../_head.php';
?>

<p>
    <button data-get="index.php">Index</button>
</p>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="id">Id</label>
    <b><?= $id ?></b>
    <br>

    <label for="name">Name</label>
    <?= text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="desc">Description</label>
    <?= textarea('desc', 'maxlenght="1000"') ?>
    <?= err('desc') ?>

    <label for="price">Price</label>
    <?= text('price', 'maxlength="5"') ?>
    <?= err('price') ?>

    <label for="photo1">Photo 1</label>
    <label class="upload">
        <?= _file('photo1', 'image/*') ?>
        <?php if (!empty($p->photos[0])) : ?>
            <img src="../_/photos/<?= $p->photos[0] ?>" alt="Product Photo 1">
        <?php else: ?>
            <img src="/_/images/photo.jpg">
        <?php endif; ?>
    </label>
    <?= err('photo1') ?>

    <label for="photo2">Photo 2</label>
    <label class="upload">
        <?= _file('photo2', 'image/*') ?>
        <?php if (!empty($p->photos[1])) : ?>
            <img src="../_/photos/<?= $p->photos[1] ?>" alt="Product Photo 2">
        <?php else: ?>
            <img src="/_/images/photo.jpg">
        <?php endif; ?>
    </label>
    <?= err('photo2') ?>

    <label for="photo3">Photo 3</label>
    <label class="upload">
        <?= _file('photo3', 'image/*') ?>
        <?php if (!empty($p->photos[2])) : ?>
            <img src="../_/photos/<?= $p->photos[2] ?>" alt="Product Photo 3">
        <?php else: ?>
            <img src="/_/images/photo.jpg">
        <?php endif; ?>
    </label>
    <?= err('photo3') ?>

    <label for="photo4">Photo 4</label>
    <label class="upload">
        <?= _file('photo4', 'image/*') ?>
        <?php if (!empty($p->photos[3])) : ?>
            <img src="../_/photos/<?= $p->photos[3] ?>" alt="Product Photo 4">
        <?php else: ?>
            <img src="/_/images/photo.jpg">
        <?php endif; ?>
    </label>
    <?= err('photo4') ?>

    <label for="category_id">Category</label>
    <?= select('category_id', $_categories) ?>
    <?= err('category_id') ?>

    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';
