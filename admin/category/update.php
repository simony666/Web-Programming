<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {
    $id = req('id');

    $stm = $db->prepare('SELECT * FROM categories WHERE category_id = ?');
    $stm->execute([$id]);
    $c = $stm->fetch();

    if (!$c) {
        redirect('index.php');
    }

    $name = $c->category_name;
    $type = $c->category_type;


}

if (is_post()) {
    $id = req('id');
    $name = req('name');
    $type = req('type');

    // Input: name
    if (!$name) {
        $err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $err['name'] = 'Maximum 100 characters';
    }

    // Input: type
    if (!$type) {
        $err['type'] = 'Required';
    }
    else if (strlen($type) > 100) {
        $err['type'] = 'Maximum 30 characters';
    }

    // DB operation
    if (!$err) {
        $stm = $db->prepare('
            UPDATE categories
            SET category_name = ?, category_type = ?
            WHERE category_id = ?
        ');
        $stm->execute([$name, $type, $id]);

        temp('info', 'Record updated');
        redirect('index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Category | Update';
include('../../_/layout/admin/header.php');
?>

<p>
    <button data-get="index.php">Index</button>
</p>

<form method="post" class="form">
    <label for="id">Id</label>
    <b><?= $id ?></b>
    <br>

    <label for="name">Name</label>
    <?= text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="type">Type</label>
    <?= text('type', 'maxlength="30"') ?>
    <?= err('type') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include('../../_/layout/admin/footer.php');