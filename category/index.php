<?php
include '../_base.php';

// ----------------------------------------------------------------------------

$arr = $db->query('SELECT * FROM categories')->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Category | Index';
include '../_head.php';
?>

<p>
    <button data-get="insert.php">Insert</button>
</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Type</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $c): ?>
    <tr>
        <td><?= $c->category_id ?></td>
        <td><?= $c->category_name ?></td>
        <td><?= $c->category_type ?></td>
        <td>
            <button data-get="update.php?id=<?= $c->category_id ?>">Update</button>
            <button data-post="delete.php?id=<?= $c->category_id ?>" data-confirm>Delete</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';