<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------
$id = req('id');
if (!$id) {
    redirect('index.php');
}

$p = get_product($id);
if (!$p) {
    redirect('index.php');
}
// ----------------------------------------------------------------------------

$_title = 'Product Detail';
include '/_/_head.php';
?>

<?php
echo "$id";
?>

<p>
    <tr>
        <?php for ($i = 1; $i <= count($p->photos); $i++) : ?>
            <?php $photo = $p->photos[$i - 1]; ?>
            <img src="/_/_/photos/<?= $photo ?>" alt="Product Photo <?= $i ?>">
        <?php endfor; ?>
    </tr>
</p>

<table class="table detail">
    <tr>
        <th>Id</th>
        <td><?= $p->product_id ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $p->product_name ?></td>
    </tr>
    <tr>
        <th>Description</th>
        <td><?= $p->product_desc ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->product_price ?></td>
    </tr>
    <tr>
        <th>Category</th>
        <td><?= $_categories[$p->category_id] ?></td>
    </tr>
    <tr>
        <th>Stock</th>
        <td><?= $p->product_stock ?></td>
    </tr>
</table>
<table>
    <td>
    <form method="post">
    <?php if ($user?->role == 'Admin') : ?>
        <button data-get="update.php?product_id=<?= $p->product_id ?>">Update</button>
        <button data-post="delete.php?product_id=<?= $p->product_id ?>">Delete</button>
    <?php endif ?>
</form>
    </td>
</table>


<?php
include '/_/_foot.php';
