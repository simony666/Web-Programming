<?php 
    include('../_/_base.php'); 
    auth('Member');

    if(is_post()){
        $u = $_SESSION['user'] ?? null;
        if (req('remove_product')){
            $id = req('product_id');
            $count = $db->query("SELECT count(*) FROM favourite_products WHERE user_id = '$u->id' AND product_id = '$id'")->fetchColumn();
            if($count>0){
                $stm = $db->prepare("DELETE FROM favourite_products WHERE user_id=? AND product_id=?");
                $stm->execute([$u->id,$id]);
            }
        }
        if (req('addCarts')){
            $cartids = req("cartids",[]);
            if (count($cartids)>0){
                foreach($cartids as $id){
                    add_cart($id, '1');
                }
                redirect("../cart/cart.php");
            }
        }
        if (req('addCart')){
            $id = req('product_id');
            add_cart($id, '1');
            redirect("../cart/cart.php");
        }
    }


    include('../_/layout/customer/_head.php');
?>

<section class="cart container my-5 py-5" id="target">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Favourite</h2>
        <hr class="mx-auto">
    </div>

    <table class="mt-5 pt-5" >
        <thead>
            <tr>
                <th colspan = 2>Product</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php //loop paste all fav item
                $fav = get_favourite();
                $arr = $fav==null?[]:get_products($fav);
                foreach($arr as $p):
            ?>
            <tr>
                <td style="width:1%;padding:0%">
                    <input type="checkbox" form="f" name="cartids[]" value="<?= $p->product_id ?>">
                </td>
                <td>
                    <div class="product-info">
                        <img src="../_/photos/products/<?= $p->photos[0] ?>" alt="">
                        <div>
                            <p><?= $p->product_name ?></p>
                            <small><span>RM</span><?= $p->product_price ?></small>
                            <br>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="post">
                                    <?php hidden("product_id", $p->product_id); ?>
                                    <input type="submit" class="remove-btn" name="remove_product" value="Remove"></input>
                        <input class="btn add-to-cart-btn" value="Add To Cart" type="submit" name="addCart"/>
                    </form>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
        <form method="post" id='f'>
            <input class="btn add-to-cart-btn" value="Add To Cart" type="submit" name="addCarts"/>
        </form>
    </table>
</section>
<?php include('../liveChat.php');?>
<?php include('../_/layout/customer/_foot.php');?>