<?php
    include('../_base.php');
    $orders = $db->query(
        "SELECT o.*, u.name 
        FROM orders AS o 
        JOIN user AS u ON o.user_id = u.id
        WHERE o.order_status = 'Prepared'
        ORDER BY o.order_id DESC"
    )->fetchAll();
    

    include('../_/adminLayout/header.php');
?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white text-capitalize">Orders
                        <a href="admin_order_history.php" class="btn btn-warning float-end">Order History</a>
                    </h4>
                    <div class="flex-row">
                        <a href="all_orderlist.php" >All Status </a> |
                        <a href="pending_orderlist.php">Pending Status </a>|
                        <a href="prepared_orderlist.php" class="text-white">Prepared Status</a>
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
                      <th>View</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php foreach($orders as $o):?>
                        <tr>
                            <td><?= $o->order_id ?></td>
                            <td><?= $o->name ?></td>
                            <td><?= $o->total_cost ?></td>
                            <td><?= $o->order_date ?></td>
                            <td><?= $o->order_status ?></td>
                            <td>
                                <button type="submit" name="update_order_btn" class="btn btn-primary mt-2">View details</button>
                            </td>
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
    include('../_/adminLayout/footer.php')
?>