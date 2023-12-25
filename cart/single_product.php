<?php 

    include ('../_base.php');
    //include ('get_featured_products.php');
    
    //TODO
    if(isset($_GET['product_id'])){
        $pId = req('product_id');

        $stm = $db->prepare(
            "SELECT * 
            FROM products 
            WHERE product_id = ?
            LIMIT 1
        ");

        $stm->execute([$pId]);
        $product = $stm->fetch(PDO::FETCH_ASSOC); 
    }else{
        redirect('index.php');
    }

    include ('_head.php');
 ?>

    <!-- Single Product -->
    <section class="container single-product my-5 pt-5">
        <div class="row mt-5">
        <?php if (!empty($product)):?>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <img class="img-fluid w-100 pb-1" src="../_/photos/products/<?php echo $product['product_image']; ?>" alt="" id="mainImg">
                    <div class="small-img-group">
                        <div class="small-img-col">
                            <img src="../_/photos/products/<?php echo $product['product_image']; ?>" alt="" class="small-img" width="100%">
                        </div>
                        <div class="small-img-col">
                            <img src="../_/photos/products/<?php echo $product['product_image2']; ?>" alt="" class="small-img" width="100%">
                        </div>
                        <div class="small-img-col">
                            <img src="../_/photos/products/<?php echo $product['product_image3']; ?>" alt="" class="small-img" width="100%">
                        </div>
                        <div class="small-img-col">
                            <img src="../_/photos/products/<?php echo $product['product_image4']; ?>" alt="" class="small-img" width="100%">
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-12 col-12">
                    <h6>Men/Shoes</h6>
                    <h3 class="py-4"><?php echo $product['product_name']; ?></h3>
                    <h2><?php echo $product['product_price']; ?></h2>

                    <form method="POST" action="cart.php">
                        <?php hidden("product_id", $product['product_id']); ?>
                        <?php hidden("product_image", $product['product_image']); ?>
                        <?php hidden("product_name", $product['product_name']); ?>
                        <?php hidden("product_price", $product['product_price']); ?>
                        <input type="number" value="1" name="product_quantity"/>
                        <button class="buy-btn" type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                    <h4 class="mt-5 mb-5">Product details</h4>
                    <span><?php echo $product['product_desc']; ?></span>
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
        <hr>
        </div>
        <div class="row mx-auto container-fluid">
        <?php foreach ($featured_products as $row):?>
            <div class="product text-center col-lg-3 col-md-4 col-sm-12" >
                <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>">
                <img src="../_/photos/products/<?php echo $row['product_image']; ?>" alt="" class="img-fluid mb-3">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">RM<?php echo $row['product_price']; ?></h4>
                <button class="buy-btn">Buy Now</button>
                </a>
            </div>
            <?php endforeach; ?>
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
<?php include'_foot.php'?>