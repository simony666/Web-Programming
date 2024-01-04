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
                    <div class="nav-bar">
                    <li class="">
                    <a href="<?= base('../../../admin/report/chart.php') ?>">Chart</a> | 
                    <a href="<?= base('../../../admin/report/chart2.php') ?>">Chart2</a> |     
                    <a href="<?= base('../../../admin/report/chart3.php') ?>">Chart3</a> | 
                    <a href="<?= base('../../../admin/report/chart4.php') ?>">Chart4</a> |        
                    <a href="<?= base('../../../admin/report/chart5.php') ?>">Chart5</a> |       
                    <a href="<?= base('../../../admin/report/chart6.php') ?>">Chart6</a> |       
                    <a href="<?= base('../../../admin/report/chart7.php') ?>">Chart5</a> |       
                    <a href="<?= base('../../../admin/report/chart8.php') ?>">Chart8</a> |       
                    </li>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include ('../_/layout/admin/footer.php');  ?>