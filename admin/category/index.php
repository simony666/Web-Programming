<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

$arr = $db->query('SELECT * FROM categories')->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Category | Index';
include '/_/_head.php';
?>

<form method="post">
    <?php if ($user?->role == 'Admin'): ?>
        <p>
            <button data-get="insert.php">Insert</button>
        </p>
    <?php endif ?>
</form>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Type</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $c): ?>
    <tr class="cat_product" data-cat='<?= $c->category_id ?>'>
        <td><?= $c->category_id ?></td>
        <td><?= $c->category_name ?></td>
        <td><?= $c->category_type ?></td>
        <td>
            <form method="post">
                <?php if ($user?->role == 'Admin'): ?>
                    <button data-get="update.php?id=<?= $c->category_id ?>">Update</button>
                    <button data-post="delete.php?id=<?= $c->category_id ?>" data-confirm>Delete</button>
                <?php endif ?>
            </form>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<script>
    $('[data-cat]').click(e => {
        console.log("clicked");
        const id = e.currentTarget.dataset.cat;
        window.location.href = 'category.php?id=' + id;
    });
</script>


<?php
include '/_/_foot.php';