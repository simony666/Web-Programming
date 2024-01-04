<?php 
    include ('../_/_base.php');
    include ('../_/layout/admin/header.php'); 
    auth('Admin');

if ($user?->role == 'Admin'): ?>
    <a href="/user/index.php">User</a>
<?php endif ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="col-lg-5 col-sm-5">
                    <div class="card">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include ('../_/layout/admin/footer.php');  ?>