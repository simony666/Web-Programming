<?php 
    include('../_/_base.php');

    // Authorization (member)
    auth('Member');

    if (is_post()) {
        if (req('remove_product')){
            $id_to_remove = post('product_id');
            remove_from_cart($id_to_remove);
            redirect();
        }
    
        $id = req('product_id');
        $unit = req('product_quantity');
        update_cart($id, $unit);
        // Redirect or display a success message
        echo '<script>alert("Product is already added to cart")</script>';
        //redirect();
    }
    
    $cart = get_cart();

    $ids = array_keys($cart);
    
    $in = in($ids);
    $stm = $db->prepare("SELECT * FROM products WHERE product_id IN ($in)");
    $stm->execute($ids);
    
    if($stm->rowCount() > 0){
        $arr = get_products($ids);
    }else{
        $total = 0;
        $arr = null;
    }

    include('../_/layout/customer/_head.php');
?>

<!-- Cart -->
<section class="cart container my-5 py-5" id="target">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Your Cart</h2>
        <hr class="mx-auto">
    </div>

    <table class="mt-5 pt-5" >
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>


    <?php if ($arr !== null): ?>
        <?php 
            $count = 0;
            $total = 0;

            foreach ($arr as $p): 
                $unit = $cart[$p->product_id] ?? 0;
                $subtotal = $p->product_price * $unit;
                $count += $unit;
                $total += $subtotal;  
            ?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="../_/photos/products/<?= $p->photos[0] ?>" alt="">
                        <div>
                            <p><?= $p->product_name ?></p>
                            <small><span>RM</span><?= $p->product_price ?></small>
                            <br>
                            <form method="post">
                                <?php hidden("product_id", $p->product_id); ?>
                                <input type="submit" class="remove-btn" name="remove_product" value="Remove"></input>
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="post" class="edit-form">
                        <?php hidden("product_id", $p->product_id); ?>
                        <input type="number" name="product_quantity" id="" value="<?= $unit ?>" min="1" max="10" >
                    </form>
                </td>
                <td>
                    <span>RM</span>
                    <span class="product-price"><?= sprintf('%.2f', $subtotal) ?></span>
                </td>
            </tr>
            
            <?php endforeach; ?>
        <?php endif; ?>
            </table>

    <div class="cart-total">
        <table>
            <tr>
                <td>Total</td>
                <td>RM<?= sprintf('%.2f', $total) ?></td>
            </tr>
        </table>
    </div>

    <!--  Store total using the temp function -->
    
    <?php if ($arr !== null): ?>
        <div class="checkout-container">
            <form method="post" action="payment.php">
                <?php foreach ($arr as $p): ?>
                    <!-- TODO -->
                    <?php hidden("product_id[]", $p->product_id); ?>
                <?php endforeach; ?>
                <input class="btn checkout-btn" value="Checkout" type="submit" name="checkout"/>
            </form>
        </div>
    <?php endif; ?>
</section>
<script>
    // (A) Non-AJAX submit
    $('select').change(e => e.target.form.submit());

    // TODO: (B) AJAX submit
    $(document).on('change', 'input', e => {
        const param = $(e.target.form).serializeArray(); // serialize: return a string,serializeArray: return an array
        //'要拿出的 document', replace param 进去 （用array = POST request, string = 用 get request）
        // #products>* = list all the children of the products
        $('#target').load(' #target>*', param); //POST 
    });
</script>
<?php include('../liveChat.php');?>
<?php include('../_/layout/customer/_foot.php'); ?>