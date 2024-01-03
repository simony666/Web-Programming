<?php

use function PHPSTORM_META\type;

include '/_/_base.php';

// ----------------------------------------------------------------------------
$photos = [];
$id = req('product_id');


if (is_get()) {
    $id = req('product_id');
    $p = get_product($id);
    if (!$p) {
        redirect('index.php');
    }
    $name = $p->product_name;
    $price = $p->product_price;
    $desc = $p->product_desc;
    $photo = $p->photos[0];
    $category_id = $p->category_id;
    $stock = $p->product_stock;
    $number = count($p->photos);
}

if (is_post()) {
    $id = req('product_id');
    $p = get_product($id);
    $name = req('name');
    $desc = req('desc');
    $price = req('price');
    $category_id = req('category_id');
    $stock = req('stock');

    //new photo upload
    $nf = get_file('new_photo');



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

    // Input: photo (if not null) for new upload
    if ($nf) {
        if (!str_starts_with($nf->type, 'image/')) {
            $err['photo'] = 'Must be image';
        } else if ($nf->size > 1 * 1024 * 1024) {
            $err['photo'] = 'Maximum 1MB';
        }
    }

    $newPhotos = [];
    for ($i = 1; $i <= count($p->photos); $i++) {
        $fileKey = "photo{$i}";
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
                    ->toFile("../_/photos/products/$newPhoto", 'image/jpeg');

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

    // Input: stock
    if (!$stock) {
        $err['stock'] = 'Required';
    } else if (!isInteger($stock)) {
        $err['stock'] = 'Must be integer';
    } else if ($stock < 0 || $stock > 1000) { // TODO
        $err['stock'] = 'Must between 1 - 999';
    }

    // DB operation
    if (!$err) {
        // TODO: Delete photo + save photo (if not null)
        if ($nf) {
            //unlink("../_/photos/$photo");

            $photo = uniqid() . '.jpg';
            require_once '/_/lib/SimpleImage.php';
            $img = new SimpleImage();
            $img->fromFile($nf->tmp_name)
                ->thumbnail(200, 200)
                ->toFile("../_/photos/products/$photo", 'image/jpeg');

            $stm = $db->prepare('INSERT INTO product_pic(id,photo) VALUES (?,?)');
            $stm->execute([$id, $photo]);
        }


        $stm = $db->prepare('
            UPDATE products
            SET product_name = ?, product_desc = ?, product_price = ?, category_id = ?, product_stock = ?
            WHERE product_id = ?
        ');
        $stm->execute([$name, $desc, $price, $category_id, $stock, $id]);


        temp('info', 'Record updated');
        redirect('index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Update';
include '/_/_head.php';
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

    <label for="photo<?= $i ?>">Photo</label>
    <label class="upload">
        <?php for ($i = 1; $i <= count($p->photos); $i++) : ?>
            <?php $photo = $p->photos[$i - 1]; ?>
            <img src="../_/photos/products/<?= $photo ?>" data-dog="<?= $photo ?>" alt="Product Photo <?= $i ?>">

        <?php endfor; ?>
    </label>
    <?= err("photo{$i}") ?>

    <label for="new_photo">Photo</label>
    <label class="upload">
        <?= _file('new_photo', 'image/*') ?>
        <img src="/_/images/photo.jpg">
    </label>
    <?= err('new_photo') ?>

    <label for="category_id">Category</label>
    <?= select('category_id', $_categories) ?>
    <?= err('category_id') ?>

    <label for="stock">Stock</label>
    <?= text('stock', 'maxlength="4"') ?>
    <?= err('stock') ?>

    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>
<script>
    $('[data-dog]').click(e => {
        if (!confirm('Are you sure want to delete this photo?')) {
            return;
        }
        photo = e.target.dataset.dog;
        $.get("./deletepic.php?id=<?= $id ?>&photo=" + photo);
        e.target.remove();
    })
</script>
<?php
include '/_/_foot.php';
