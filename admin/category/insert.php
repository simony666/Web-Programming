<?php
include '../../_/_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {
    // TODO: Generate next category id
    // $max = $db->query('SELECT MAX(category_id) FROM categories')->fetchColumn() ?? 'C000';
    // $n = substr ($max, 1);
    // $id = sprintf('C%03d', min($n + 1, 999));

    
}

if (is_post()) {
    $id = req('id');
    $name = req('name');
    $type = req('type');

    // Input: id
    if (!$id) {
        $err['id'] = 'Required';
    }
    else if (!preg_match('/^C\d{3}$/', $id)) {
        $err['id'] = 'Invalid format';
    }
    else {
        $stm = $db->prepare('SELECT COUNT(*) FROM categories WHERE category_id = ?');
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

    // Input: type
    if (!$type) {
        $err['type'] = 'Required';
    }
    else if (strlen($type) > 30) {
        $err['type'] = 'Maximum 30 characters';
    }

    // DB operation
    if (!$err) {
        $stm = $db->prepare('
            INSERT INTO categories (category_id, category_name, category_type)
            VALUES (?, ?, ?)
        ');
        $stm->execute([$id, $name, $type]);

        temp('info', 'Record inserted');
        redirect('index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Category | Insert';
include('../../_/layout/admin/header.php');
?>

<p>
    <button data-get="index.php">Index</button>
</p>

<form method="post" class="form">
    <label for="id">Id</label>
    <?= text('id', 'maxlength="4" data-upper') ?>
    <?= err('id') ?>

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