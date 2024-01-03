<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------
$id = req('id');
if (!$id) {
    redirect('index.php');
}

$stm = $db->prepare("SELECT * FROM categories WHERE category_id = ?");
$stm->execute([$id]);
$c = $stm->fetch();

$stm = $db->prepare("SELECT product_id FROM products WHERE category_id = ?");
$stm->execute([$id]);
$p_arr = array();
foreach ($stm->fetchAll() as $po){
    $p_arr[] = get_product($po->product_id);
}



// ----------------------------------------------------------------------------



$_title = 'Category | product';
include '/_/_head.php';
?>

<style>
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

<?php 
echo "Category = $id"; 
?>
<p><?= count($p_arr) ?> record(s)</p>

<div id="cat_product">
    <?php foreach ($p_arr as $p): ?>
        <div class="product">
            <img src="../_/photos/products/<?= $p->photos[0] ?>"
                 data-get="../product/product_detail.php?id=<?= $p->product_id ?>">
            <div>
                <?= $p->product_name ?> |
                RM <?= $p->product_price ?>
            </div>
        </div>
    <?php endforeach ?>
</div>



<script>
    // (B) AJAX submit
    $(document).on('change', 'select', e => {
        const param = $(e.target.form).serializeArray();
        $('#products').load(' #products >', param); // POST
    });
</script>
<?php
include '/_/_foot.php';