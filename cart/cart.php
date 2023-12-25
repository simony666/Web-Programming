<?php 
    include('../_base.php');

    //TODO
    if (isset($_POST['add_to_cart'])) {
        if (is_post()) {
            $id = post('product_id');
            $name = post('product_name');
            $price = post('product_price');
            $image = post('product_image');
            $qty = post('product_quantity');
    
            // Validate input data if needed
    
            // Get the current cart
            $cart = get_cart();
    
            // Check if the product is already in the cart
            if (isset($cart[$id])) {
                // Product already in the cart, update quantity or any other details
                $cart[$id]['product_quantity'] += $qty;
            } else {
                // Product not in the cart, add it
                $cart[$id] = array(
                    'product_name' => $name,
                    'product_price' => $price,
                    'product_image' => $image,
                    'product_quantity' => $qty
                );
            }
    
            // replace the cart in the session
            set_cart($cart);
            
            // Fetch details for the products in the cart
            $ids = array_keys($cart);
            $in = in($ids);
            $stm = $db->prepare("SELECT * FROM products WHERE product_id IN ($in)");
            $stm->execute($ids);
            $arr = $stm->fetchAll();

            // Redirect or display a success message
            echo '<script>alert("Product was already added to cart")</script>';
            
        }
        //remove products from cart
    } else if (post('remove_product')) {
        $id_to_remove = post('product_id');
        remove_from_cart($id_to_remove);

        // Fetch details for the remaining products in the cart
        $cart = get_cart();
        $ids = array_keys($cart);

        if (!empty($ids)) {
            $in = in($ids);
            $stm = $db->prepare("SELECT * FROM products WHERE product_id IN ($in)");
            $stm->execute($ids);
            $arr = $stm->fetchAll();
        } else {
            // No remaining products in the cart
            $arr = null;
            echo '<script>alert("Your cart is empty. Please go and add some items inside your cart.")</script>';
            echo '<script>window.location.href= "index.php";</script>';
        }

        
    }else if (post('edit_quantity')) {
        $product_id = post('product_id');
        // new product quantity
        $product_qty = post('product_quantity');


         // update back the quantity to the cart
        update_cart($product_id,$product_qty);

        // Fetch details for the remaining products in the cart
        $cart = get_cart();
        $ids = array_keys($cart);

        if (!empty($ids)) {
            $in = in($ids);
            $stm = $db->prepare("SELECT * FROM products WHERE product_id IN ($in)");
            $stm->execute($ids);
            $arr = $stm->fetchAll();
        } else {
            // No remaining products in the cart
            $arr = null;
            echo '<script>alert("Your cart is empty. Please go and add some items inside your cart.")</script>';
            echo '<script>window.location.href= "index.php";</script>';
        }

    }else {
            // Redirect if the request is not a POST or if 'add_to_cart' is not set
            echo '<script>alert("Your cart is empty. Please go and add some items inside your cart.")</script>';
            redirect('index.php');
    }
    

    include('_head.php');
    
?>

<!-- Cart -->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bolde">Your Cart</h2>
        <hr>
    </div>

    <table class="mt-5 pt-5">
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
                $product_id = $p->product_id;
                $unit = $cart[$product_id]['product_quantity'] ?? 0;
                // var_dump($unit); 
                $subtotal = $p->product_price * $unit;
                $count += $unit;
                $total += $subtotal;  
            ?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="../_/photos/products/<?= $p->product_image ?>" alt="">
                        <div>
                            <p><?= $p->product_name ?></p>
                            <small><span>RM</span><?= $p->product_price ?></small>
                            <br>
                            <form method="post" action="cart.php">
                                <?php hidden("product_id", $p->product_id); ?>
                                <input type="submit" class="remove-btn" name="remove_product" value="Remove"></input>
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="post" action="cart.php">
                        <?php hidden("product_id", $product_id); ?>
                        <input type="number" name="product_quantity" id="" value="<?= $unit ?>" min="1" max="10" >
                        <input type="submit" class="edit-btn" name="edit_quantity" value="Edit"></input>
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
    <?= temp('cart_total', $total);  ?>

    <div class="checkout-container">
        <form method="post" action="checkout.php">
            <input class="btn checkout-btn" value="Checkout" type="submit" name="checkout"/>
        </form>
    </div>
</section>

<?php include'_foot.php'?>
