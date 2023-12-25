<?php 
  include('../_base.php'); 
  include('_head.php');

  // ----------------------------------------------------------------------------

// get the cart to check the item is it inside the cart
  //$cart = get_cart();

  // $category_id = req('category_id');

?>

<!-- Home -->
<section id="home">
    <div class="container">
      <h5>NEW ARRIVALS</h5>
      <h1><span>Best Prices</span> This Season</h1>
      <p>Unique offers the best products for the most affordable prices</p>
      <button>Shop Now</button>
    </div>
  </section>

  <!-- Brand -->
  <section id="brand" class="container">
    <div class="row">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="images/brand/brand1.png" alt="">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="images/brand/brand2.jpg" alt="">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="images/brand/brand3.png" alt="">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="images/brand/brand4.png" alt="">
    </div>
  </section>

  <!-- New -->
  <section id="new" class="w-100">
    <div class="row p-0 m-0">
      <!-- One -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="images/new/goods_09_450698.webp">
        <div class="details">
          <h2>Extremely Awesome Shoes</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>
      <!-- Two -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="images/new/goods_09_454218.avif">
        <div class="details">
          <h2>Awesome Jackets</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>
      <!-- Three -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="images/new/goods_09_454373.png">
        <div class="details">
          <h2>50% OFF Watches</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>
    </div>
  </section>

<!-- Features -->
  <section id="featured" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our Featured</h3>
      <hr class="mx-auto">
      <p>Here you can check out our featured products</p>
    </div>
    <div class="row mx-auto container-fluid">
      <?php $featured_products = get_featured_products();

       foreach ($featured_products as $row):?>
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
          <a href="<?php echo "single_product.php?product_id=".$row['product_id']?> ">
            <button class="buy-btn">Buy Now</button>
          </a>
        </a>
      </div>
    <?php endforeach; ?>
    </div>  

  </section>

<!-- Banner -->
<section id="banner" class="my-5 py-5">
  <div class="container">
    <h4>MID SEASON'S SALE</h4>
    <h1>Autumn Collection<br> Up to 30% OFF</h1>
    <button class="text-uppercase">shop now</button>
  </div>
</section>

<!-- Clothes -->
<section id="featured" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>Dresses & Coats</h3>
    <hr class="mx-auto">
    <p>Here you can check out our amazing clothes</p>
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
  ?>
    </div>  
</section>

<?php include('_foot.php'); ?>