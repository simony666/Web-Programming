<?php 

    include ('../_/_base.php');
    $featured_products = get_featured_products();
    
    if(req('product_id')){
        $pId = req('product_id');

        $product = get_product($pId);
    }else{
        redirect('index.php');
    }

    include ('../_/layout/customer/_head.php');
    
 ?>

    <!-- Single Product -->
    <section class="container single-product my-5 pt-5">
        <div class="row mt-5">
        <?php if ($product):?>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <img class="img-fluid w-100 pb-1" src="../_/photos/products/<?= $product->photos[0] ?>" alt="" id="mainImg">
                    <div class="small-img-group">
                        <?php foreach($product->photos as $photo):?>
                        <div class="small-img-col">
                            <img src="../_/photos/products/<?= $photo ?>" alt="" class="small-img" width="100%">
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-12 col-12">
                    <h6>Men/Shoes</h6>
                    <h3 class="py-4"><?= $product->product_name ?></h3>
                    <h2><?= $product->product_price ?></h2>

                    <form method="POST" action="<?=base('cart/cart.php')?>">
                        <?php hidden("product_id", $product->product_id )?>
                        <!-- hidden image ,name, price -->
                        <input type="number" value="1" name="product_quantity"/>
                        <button class="buy-btn" type="submit" name="add_to_cart" value="1">Add to Cart</button>
                    </form>
                    <h4 class="mt-5 mb-5">Product details</h4>
                    <span><?= $product->product_desc ?></span>
                </div>
            
            <?php else: ?>
                <p>No product found.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related products -->
    <section id="related-products" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
        <h3>Related Products</h3>
        <hr class="mx-auto">
        </div>
        <div class="row mx-auto container-fluid">
        <?php featured_products(get_featured_products()); ?>
        </div>
    </section>

    <!-- click small image -->
    <script>
        
        var mainImg = document.getElementById("mainImg");
        var smallImg = document.getElementsByClassName("small-img"); // will return array

        for(let i=0; i<4; i++){
                smallImg[i].onclick = function(){
                mainImg.src =smallImg[i].src;
            }
        }
        
    </script>
<?php include('../liveChat.php');?>
<?php include('../_/layout/customer/_foot.php'); ?>