<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {
    // TODO: Generate next product id
    $max = $db->query('SELECT MAX(product_id) FROM products')->fetchColumn() ?? 'P000';
    $n = substr ($max, 1);
    $id = sprintf('P%03d', min($n + 1, 999));

    
}

if (is_post()) {
    $id = req('id');
    $name = req('name');
    $desc = req('desc');
    $price = req('price');
    $f = get_file('photo');
    $category_id = req('category_id');

    // Input: id
    if (!$id) {
        $err['id'] = 'Required';
    }
    else if (!preg_match('/^P\d{3}$/', $id)) {
        $err['id'] = 'Invalid format';
    }
    else {
        $stm = $db->prepare('SELECT COUNT(*) FROM products WHERE product_id = ?');
        $stm->execute([$id]);

        if ($stm->fetchColumn()) {
            $err['id'] = 'Duplicated';
        }
    }

    // Input: name
    if (!$name) {
        $err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $err['name'] = 'Maximum 100 characters';
    }

    // Input: desc
    if (!$desc) {
        $err['desc'] = 'Required';
    } 
    else if (strlen($desc) > 1000) {
        $err['desc'] = 'MAximum 1000 charecters';
    }

    // Input: price
    if ($price == '') { // TODO
        $err['price'] = 'Required';
    }
    else if (!is_money($price)) { // TODO
        $err['price'] = 'Must be money';
    }
    else if ($price < 0.01 || $price > 9999.99) { // TODO
        $err['price'] = 'Must between 0.01 - 9999.99';
    }

    // Input: photo
    if (!$f) {
        $err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $err['photo'] = 'Maximum 1MB';
    }

    // Input: category_id
    if (!$category_id) {
        $err['category_id'] = 'Required';
    }
    else if (!array_key_exists($category_id, $_categories)) {
        $err['category_id'] = 'Not exists';
    }

    // DB operation
    if (!$err) {
        // TODO: Save photo
        $photo = uniqid() . '.jpg';
        require_once '../lib/SimpleImage.php';
        $img = new SimpleImage();
        $img->fromFile($f->tmp_name)
            ->thumbnail(200, 200)
            ->toFile("../_/photos/$photo", 'image/jpeg');
        

        $stm = $db->prepare('
            INSERT INTO products (product_id, product_name, product_desc, product_price, category_id)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stm->execute([$id, $name, $desc, $price, $category_id]);

        $stm = $db->prepare("INSERT INTO product_pic (id, photo) VALUES (?, ?)");
        $stm->execute([$id, $photo]);

        temp('info', 'Record inserted');
        redirect('index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Insert';
include '../_head.php';
?>

<p>
    <button data-get="index.php">Index</button>
</p>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="id">Id</label>
    <?= text('id', 'maxlength="4" data-upper') ?>
    <?= err('id') ?>

    <label for="name">Name</label>
    <?= text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="desc">Description</label>
    <?= text('desc', 'maxlength="1000"') ?>
    <?= err('desc') ?>

    <label for="price">Price</label>
    <?= text('price', 'maxlength="5"') ?>
    <?= err('price') ?>

    <label for="photo">Photo</label>
    <label class="upload">
        <?= _file('photo', 'image/*') ?>
        <img src="/_/images/photo.jpg">
    </label>
    <?= err('photo') ?>

    <label for="category_id">Category</label>
    <?= select('category_id', $_categories) ?>
    <?= err('category_id') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';