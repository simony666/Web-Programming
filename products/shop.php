<?php 
    include('../_/_base.php'); 

    // Paging (class)
    $page = req('page',1);
    $page = max($page,1);

    require_once '../_/lib/Pager.php';
    $page = new Pager('SELECT * FROM products', [], 15, $page);
    $arr = $page->result;

    $category_id = 'CAF01';
    // if user uses search features
    // if (post('search')) {
    if (is_post()) {
      $category_id = req('category_id');
      $price = req('price');

      // Check if search criteria are provided
      if (!$category_id && !$price) {
          // Search criteria are empty, return all products
          $products = get_products();
      } else {
          $stm = $db->prepare(
            "SELECT p.product_id
            FROM products AS p
            JOIN categories AS c ON p.category_id = c.category_id
            WHERE c.category_id = ?
            AND product_price <= ?
        ");

        $stm->execute([$category_id, $price]);
        $products = array();
        foreach($stm->fetchAll() as $p){
          $products[] = get_product($p->product_id);
        }
        
        //var_dump($products);
      }
    } else {
      // User didn't use search features, return all products
      $products = get_products();
    }
    $fav_p = get_favourite();

      include('../_/layout/customer/_head.php');
?>



  <!-- Search -->
<div class="shop">
    <section class="my-5 py-5 ms-2">
    <div class="container mt-5 py-5 search-product">
      <h4>Search Products</h4>
      <hr>
      <form method="post" >
          <div class="mx-auto container row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <label class="fw-bold">Category</label>
                <div class="form-check search">
                  <?php
                    $items = $db->query(
                      "SELECT category_id, category_name
                      FROM categories 
                    ")->fetchAll(PDO::FETCH_KEY_PAIR);
                    ?>
                  <div>
                    <?= radios('category_id', $items,true);?>
                  </div>
              </div>
            </div>
          </div>

          <div class="row mx-auto container mt-5">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <p class="fw-bold">Price</p>
              <div class="slider">
                <input type="range" class="form-range w-50 search" min="50" max="2500" id="customerRange2" name="price" value="<?= $price ?? 50 ?>" oninput="rangeValue.innerText = this.value">
              <!-- <div class="w-50">
                <span style="float:left;">50</span>
                <span style="float:right;">2000</span>
              </div> -->
                <p id="rangeValue">50</p>
              </div>
              
            </div>
          </div>

          <div class="form_group my-3 mx-3">
            <input type="submit" name="search" value="Search" class="search-btn btn-primary">
            <!-- <button>Submit</button> -->
          </div>
        </div>
      </form>
  </section>

  <!-- Shops -->
  <section id="shop" class="my-5 pb-5" >
      <div class="container text-center py-5 mt-5 " >
        <h3>Our Products</h3>
        <hr class="mx-auto">
        <p>Here you can check out our featured products</p>
      </div>

      <div class="row mx-auto container-fluid" id="target">
      <?php 
      if (count($products) > 0):
        foreach ($products as $p):
      ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12" >
            <?php if (in_array($p->product_id,$fav_p)):?>
              <i data-fav="<?=  $p->product_id ?>" class='red fa-solid fa-heart' style='z-index:100;'></i>
            <?php else:?>
              <i data-fav="<?=  $p->product_id ?>" class='red fa-regular fa-heart' style='z-index:100;'></i>
            <?php endif;?>
          <a href="./single_product.php?product_id=<?=  $p->product_id ?>">
            <img src="../_/photos/products/<?=  $p->photos[0] ?>" alt="" class="img-fluid mb-3">
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?=  $p->product_name ?></h5>
            <h4 class="p-price">RM<?=  $p->product_price ?></h4>
            <button class="buy-btn">Buy Now</button>
          </a>
        </div>
      <?php 
        endforeach; 
      else:?>
       <div class="text-center py-5 mt-5">
        <p>No Product Match</p>
       </div>
      
      <?php
      endif;
      ?>
      </div>

      <nav aria-label="Page navigation example">
        <?= $page->html() ?>
      </nav>
  </section>
</div>
  


  

<script>
    // TODO: AJAX
    $(document).on('submit','form', e=>{
        e.preventDefault();
        const param = $(e.target).serializeArray();
        console.log(param);
        $('#target').load(' #target >', param);
    });
</script>

<?php include('../liveChat.php');?>
<?php include('../_/layout/customer/_foot.php');?>