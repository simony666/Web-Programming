<?php
    include('../_/_base.php');
    $orders = $db->query(
        "SELECT o.*, u.name 
        FROM orders AS o 
        JOIN user AS u ON o.user_id = u.id
        WHERE o.order_status = 2
        OR o.order_status = 3
        ORDER BY o.order_id DESC"
    )->fetchAll();
    

    include('../_/layout/admin/header.php');
?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
                <div class="card-header bg-primary">
                    <a href="all_orderlist.php" class="btn-warning float-end"><i class="fa fa-reply pe-2"></i>Back</a>
                    <h4 class="text-white text-capitalize">Order</h4>
                    <div class="flex-row">
                        <h6 class="text-white">All Status </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr class="text-center">
                      <th>Order Id</th>
                      <th>Username</th>
                      <th>Price (RM)</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php foreach($orders as $o):?>
                        <tr>
                            <td><?= $o->order_id ?></td>
                            <td><?= $o->name ?></td>
                            <td><?= $o->total_cost ?></td>
                            <td><?= $o->order_date ?></td>
                            <td><?= $_orderStatus[$o->order_status] ?></td>
                            
                        </tr>
                        <?php endforeach;?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>

<?php 
    include('../_/layout/admin/footer.php');
?>