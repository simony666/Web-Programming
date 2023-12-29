<?php 
    include('../_base.php'); 

    // use the search section
  if(req('search')){
    $category = req('category');
    $price = req('price');
    
    $stm = $db->prepare(
        "SELECT p.*, c.category_type
        FROM products  AS p,
        categories AS c
        WHERE p.category_id = c.category_id
        AND c.category_type = ?
        AND product_price <= ?
      ");

    $stm->execute([$category,$price]);
    $products = $stm->fetchAll();

    // return all products
  }else{
    $stm = $db->prepare(
      " SELECT *
        FROM products  
      ");

      $stm->execute();
      $products = $stm->fetchAll();
  }

      include('../_/customerLayout/_head.php');
?>

<!-- Search -->
<section id="search" class="my-5 py-5 ms-2">
  <div class="container mt-5 py-5">
    <p>Search Products</p>
    <hr>
  </div>
<!-- get -->
    <form action="shop.php">
        <div class="mx-auto container row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <p>Category</p>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_one" value="Shirts">
                <label for="flexRadioDefault2" class="form-check-label">
                  Shirts
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Pants" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Pants
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Shoes" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Shoes
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Watches" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Watches
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Skirts" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Skirts
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Jackets" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Jackets
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category_two" value="Bags" checked>
                <label for="flexRadioDefault2" class="form-check-label">
                  Bags
                </label>
              </div>
          </div>
        </div>

        <div class="row mx-auto container mt-5">
          <div class="col-lg-12 col-md-12 col-sm-12">

            <p>Price</p>
            <input type="range" class="form-range w-50" min="1" max="1000" id="customerRange2" name="price" value="100">
            <div class="w-50">
              <span style="float:left;">1</span>
              <span style="float:right;">2000</span>
            </div>
          </div>
        </div>

        <div class="form_grop my-3 mx-3">
          <input type="submit" name="search" value = Search class="btn btn-primary">
        </div>
    </form>
</section>


<!-- Shops -->
<section id="shop" class="my-5 pb-5" >
    <div class="container text-center py-5" style="margin-top: 4.5rem;">
      <h3>Our Products</h3>
      <hr class="mx-auto">
      <p>Here you can check out our featured products</p>
    </div>

    <?php foreach($products as $p ):?>
    <div class="row mx-auto container">
      <!-- onclick="window.location.href='single_product.php'" -->
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img src="../_/photos/products/<?=  $p->product_image ?>" alt="" class="img-fluid mb-3">
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?= $p->product_name?></h5>
        <h4 class="p-price"><?= $p->product_price?></h4>
        <a class="btn shop-buy-btn" href="<?= "single_product.php?product_id=".$p->product_id ?>">Buy Now</a>
      </div>
  
      <?php endforeach?>

      <nav aria-label="Page navigation example">
        <ul class="pagination mt-5">
            <li class="page-item"><a href="#" class="page-link">Previous</a></li>
            <li class="page-item"><a href="#" class="page-link">1</a></li> 
            <li class="page-item"><a href="#" class="page-link">2</a></li>
            <li class="page-item"><a href="#" class="page-link">3</a></li>
            <li class="page-item"><a href="#" class="page-link">Next</a></li>
            
        </ul>
      </nav>

    </div>
  </section>

<?php include('../_/customerLayout/_foot.php');?>