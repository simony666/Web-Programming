<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------
// $_categories = $db->query('SELECT category_id, name FROM categories')->fetchAll(PDO::FETCH_COLUMN);

if (is_get()) {
    // Generate next product id
    $max = $db->query('SELECT MAX(product_id) FROM products')->fetchColumn() ?? 'P0000'; // fetchColum() fetch one value, 不成功就 P000

    // what value you want to read, read how much, until where by default = read all
    $n = substr($max,1);
    // 3 digits, if not enough use the 0 value
    $id = sprintf('P%04d', min($n + 1,999));
}

if (is_post()) {
    $id = req('product_id');
    $name = req('product_name');
    $desc = req('product_desc');
    $price = req('product_price');
    $f = get_file('product_image');
    $f2 = get_file('product_image2');
    $f3 = get_file('product_image3');
    $f4 = get_file('product_image4');
    //$category_id = req('category_id');
        
    // Input: id
    if (!$id) {
        $err['product_id'] = 'Required';
    }
    else if (!preg_match('/^P\d{4}$/', $id)) {
        $err['product_id'] = 'Invalid format';
    }
    else {
        $stm = $db->prepare('SELECT COUNT(*) FROM products WHERE product_id = ?');
        $stm->execute([$id]);

        if ($stm->fetchColumn()) {
            $err['product_id'] = 'Duplicated';
        }
    }


    // Input: photo
    if (!$f) { 
        $err['photo'] = 'Required';
    }
    // file type must start with image
    // $f->type (object 写法)
    else if (!str_starts_with($f->type, 'image/')) { 
        $err['product_image'] = 'Must be image';
    }
    else if ($f->size > 1*1024*1024*10) { // TODO
        $err['product_image'] = 'Maximum 10MB';
    }

    // Input: category_id
    // if (!$category_id) {
    //     $err['category_id'] = 'Required';
    // }
    // else if (!in_array($category_id, $_categories)) {
    //     $err['category_id'] = 'Not exists';
    // }

    // Processing
    if (!$err) {
        // suitable for PDF
        // move_uploaded_file($f->tmp_name, "_/photos/$f->name");

        // uniqid() = a function that auto generate unique id based on the timestamp
        $photo = uniqid() . '.jpg';
        // if use include, if failed will get warning.
        // if use require will get error, cannot run
        require_once '../../_/lib/SimpleImage.php';
        
        $img = new SimpleImage();
        $img->fromFile($f->tmp_name)
            ->thumbnail(200,200)
            // convert all the image file to jpeg file
            ->toFile("../../_/photos/products/$photo",'image/jpeg');

        //image2 
        $photo2 = uniqid() . '.jpg';
        // if use include, if failed will get warning.
        // if use require will get error, cannot run
        
        $img = new SimpleImage();
        $img->fromFile($f2->tmp_name)
            ->thumbnail(200,200)
            // convert all the image file to jpeg file
            ->toFile("../../_/photos/products/$photo2",'image/jpeg');

        //image2 
        $photo3 = uniqid() . '.jpg';
        // if use include, if failed will get warning.
        // if use require will get error, cannot run
        
        $img = new SimpleImage();
        $img->fromFile($f3->tmp_name)
            ->thumbnail(200,200)
            // convert all the image file to jpeg file
            ->toFile("../../_/photos/products/$photo3",'image/jpeg');


        //image2 
        $photo4 = uniqid() . '.jpg';
        // if use include, if failed will get warning.
        // if use require will get error, cannot run
        
        $img = new SimpleImage();
        $img->fromFile($f4->tmp_name)
            ->thumbnail(200,200)
            // convert all the image file to jpeg file
            ->toFile("../../_/photos/products/$photo4",'image/jpeg');
        //product_image,
        $stm = $db->prepare('
            INSERT INTO products (product_id, product_name, product_desc, product_price, product_image,product_image2,product_image3, product_image4)
            VALUES (?, ?, ?, ?, ?, ?, ?,?)
        ');
        $stm->execute([$id,$name,$desc,$price, $photo, $photo2, $photo3, $photo4]);

        temp('info', 'Photo uploaded');
        redirect(); // redirect = reload the page
    }
}

// ----------------------------------------------------------------------------

$_title = 'Upload';
include '../../_/_head.php';
?>

<!-- TODO -->
<!-- upload file need to use "post" -->
<!-- !!! enctype="multipart/form-data" 没有写会upload 不到file-->
<form method="post" class="form" enctype="multipart/form-data">
    <label for="id">Id</label>
    <?= text('product_id', 'maxlength="5" data-upper') ?>
    <?= err('product_id') ?>

    <label for="name">Name</label>
    <?= text('product_name', 'maxlength="100"') ?>
    <?= err('product_name') ?>

    <label for="name">Desc</label>
    <?= text('product_desc', 'maxlength="1000"') ?>
    <?= err('product_desc') ?>

    <label for="price">Price</label>
    <?= text('product_price', 'maxlength="5"') ?>
    <?= err('product_price') ?>

    <label for="photo">Photo</label>

    <!-- whatever inside the label 都会被选中 -->
    <label class="upload"> 
        <!-- 
            如果只放 image = all files 
            image/* = image files
        -->
        <?= _file('product_image','image/*') ?>
        <img src="/_/images/photo.jpg" alt="">
    </label>
    <?= err('product_image') ?>

    <label for="photo">Photo 2</label>
    <label class="upload"> 
        <?= _file('product_image2','image/*') ?>
        <img src="/_/images/photo.jpg" alt="">
    </label>
    <?= err('product_image2') ?>

    <label for="photo">Photo 3</label>
    <label class="upload"> 
        <?= _file('product_image3','image/*') ?>
        <img src="/_/images/photo.jpg" alt="">
    </label>
    <?= err('product_image3') ?>

    <label for="photo">Photo 4</label>
    <label class="upload"> 
        <?= _file('product_image4','image/*') ?>
        <img src="/_/images/photo.jpg" alt="">
    </label>
    <?= err('product_image4') ?>


    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../../_/_foot.php';