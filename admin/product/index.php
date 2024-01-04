<?php
include '../../_/_base.php';
auth('Admin');

// ----------------------------------------------------------------------------

$arr = get_products();
$photos = $p->photos ?? [];

// ----------------------------------------------------------------------------


$_title = 'Product | Index';
include('../../_/layout/admin/header.php');
?>

<style>
    /* TODO */
    .popup {
        display: grid;
        grid: auto / repeat(4, auto);
        gap: 1px;
    }

    .popup img {
        outline: 1px solid #333;
        width: 50px;
        height: 50px;
    }

    #cat_product {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product {
        border: 1px solid #333;
        width: 200px;
        height: 200px;
        position: relative;
    }

    .product img {
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .product form,
    .product div {
        position: absolute;
        background: #0009;
        color: #fff;
        padding: 5px;
        text-align: center;
    }

    .product form {
        inset: 0 0 auto auto;
    }

    .product div {
        inset: auto 0 0 0;
    }
</style>

<form method="post">
    <?php if ($user?->role == 'Admin') : ?>
        <p>
            <button data-get="./insert.php">Insert</button>
        </p>
    <?php endif ?>

    <button id="toggleTableStyle">Toggle Table Style</button>

    <p><?= count($arr) ?> record(s)</p>

    <!--Table view-->
    <table class="table pro_table">
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
                        <?php if ($user?->role == 'Admin') : ?>
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

    <!--Photo View-->
    <div id="cat_product" class="pro_cat_product">
    <?php foreach ($arr as $p): ?>
        <div class="product">
            <img src="../../_/photos/products/<?= $p->photos[0] ?>"
                 data-get="../product/product.php?id=<?= $p->product_id ?>">
            <div>
                <?= $p->product_name ?> |
                RM <?= $p->product_price ?>
            </div>
        </div>
    <?php endforeach ?>
</div>

    <script>
    $(document).ready(function () {
        $('.pro_table').hide();

        $('#toggleTableStyle').click((e)=>{
            e.preventDefault();
            $('.pro_table').toggle();
            $('#cat_product').toggle();
        });

        $('[data-cat]').click(function (e) {
            const id = e.currentTarget.dataset.cat;
            window.location.href = 'product.php?id=' + id;
        });
    });
    </script>

    <?php
    include('../../_/layout/admin/footer.php');
