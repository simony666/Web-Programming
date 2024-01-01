<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// $arr = [get_product('P0005')];

// $arr_img = $db->query("SELECT * FROM product_pic");
// $stm = $db->prepare('
//     SELECT i.photo
//     FROM products AS p, product_pic AS i
//     WHERE p.product_id = i.id
//     AND p.product_id = ?
// ');
// foreach ($arr_img as $o) {
//     $stm->execute([$o->id]);
//     $o->photos = $stm->fetchAll(PDO::FETCH_COLUMN);
// }

// $product->photos[0];
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

<p>
    <button data-get="insert.php">Insert</button>

</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Desc</th>
        <th>Price</th>
        <th>Category</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $p) : ?>
        <tr>
            <td><?= $p->product_id ?></td>
            <td><?= $p->product_name ?></td>
            <td><?= $p->product_desc ?></td>
            <td><?= $p->product_price ?></td>
            <td><?= $_categories[$p->category_id] ?></td>
            <td>
                <button data-get="update.php?product_id=<?= $p->product_id ?>">Update</button>
                <button data-post="delete.php?product_id=<?= $p->product_id ?>">Delete</button>
                <!-- TODO -->
                <div class="popup">
                <?php
                // foreach ($p->$photos as $photo) {
                //     echo "<img src='/_/photos/$photo'> ";
                // }
                if (!empty($p->photos)) {
                    foreach ($p->photos as $photo) {
                        echo "<img src='/_/photos/$photo'> ";
                    }
                }
                ?>
            </div>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';
