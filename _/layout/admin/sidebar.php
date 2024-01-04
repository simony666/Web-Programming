<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start   bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="../../admin/index.php" target="_blank">
      <img src="/_/images/Unique small.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">Admin Dashboard</span>
    </a>
  </div>


  <hr class="horizontal light mt-0 mb-2">

  <div class="collapse navbar-collapse  w-auto ps" id="sidenav-collapse-main">
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link text-white " href="<?=base('admin/category/index.php')?>"> 
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Category</span>
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link text-white " href="<?=base('admin/product/index.php')?>"> 
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Product</span>
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link text-white " href="<?=base('admin/all_orderlist.php')?>"> 
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Orders</span>
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link text-white " href="/admin/category/insert.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">add</i>
            </div>
            <span class="nav-link-text ms-1">Add Category</span>
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link text-white " href="/admin/product/index.php"> 
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">All Products</span>
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link text-white " href="/admin/product/insert.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">add</i>
            </div>
            <span class="nav-link-text ms-1">Add Products</span>
        </a>
        </li>
 
        
    </ul>
  </div>
  
</aside>