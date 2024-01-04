
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <link rel="stylesheet" href="/_/css/style.css">
    <script src="/_/js/app.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top py-3 ">
      <div class="container-fluid">
        <a href="<?=base('index.php')?>"><img src="/_/images/Unique small.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="<?=base('index.php')?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?=base('products/shop.php')?>">Shop</a>
            </li>
            

            <li class="nav-item">
              <a href="<?=base('products/favourite.php')?>"><i class="fa-solid fa-heart"></i></a>
              <a href="<?=base('cart/cart.php')?>"><i class="fas fa-shopping-cart"></i></a>
              <a href="<?= base("user/profile.php")?>"><i class="fas fa-user"></i></a>
              <?php if ($user){?>
                <a href="<?= base("logout.php")?>"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
              <?}?>
            </li>
          </ul>
        </div>
      </div>
  </nav>