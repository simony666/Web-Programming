<?php
include '../_base.php';

// ----------------------------------------------------------------------------

$arr = get_products();
$photos = $p->photos ?? [];

// ----------------------------------------------------------------------------


$_title = 'Product | Index';
include '../_head.php';
?>

<style>
    /* TODO */
    .popup {
        display:grid;
        grid: auto / repeat(4, auto);
        gap: 1px;
    }

    .popup img {
        outline: 1px solid #333;
        width: 50px;
        height: 50px;
    }
</style>

<form method="post">
    <?php if ($user?->role == 'Admin'): ?>
        <p>
            <button data-get="insert.php">Insert</button>
        </p>
    <?php endif ?>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Desc</th>
        <th>Price</th>
        <th>Category</th>
        <th>Stock</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $p) : ?>
        <tr class="pro_detail" data-cat='<?= $p->product_id ?>'>
            <td><?= $p->product_id ?></td>
            <td><?= $p->product_name ?></td>
            <td><?= $p->product_desc ?></td>
            <td><?= $p->product_price ?></td>
            <td><?= $_categories[$p->category_id] ?></td>
            <td><?= $p->product_stock ?></td>
            <td>
            <form method="post">
                <?php if ($user?->role == 'Admin'): ?>
                    <button data-get="update.php?product_id=<?= $p->product_id ?>">Update</button>
                    <button data-post="delete.php?product_id=<?= $p->product_id ?>">Delete</button>
                <?php endif ?>
            </form>
                    <!-- TODO -->
                    <div class="popup">
                        <?php
                // foreach ($p->$photos as $photo) {
                    //     echo "<img src='/_/photos/$photo'> ";
                    // }
                    if (!empty($p->photos)) {
                        foreach ($p->photos as $photo) {
                        echo "<img src='/_/photos/products/$photo'> ";
                    }
                }
                ?>
            </div>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<script>
    $('[data-cat]').click(e => {
        console.log("clicked");
        const id = e.currentTarget.dataset.cat;
        window.location.href = 'product_detail.php?id=' + id;
    });
</script>

<?php
include '../_foot.php';