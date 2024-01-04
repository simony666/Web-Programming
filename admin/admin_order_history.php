<?php
    include('../_/_base.php');
    auth('Admin');
    // $orders = $db->query(
    //     "SELECT o.*, u.name 
    //     FROM orders AS o 
    //     JOIN user AS u ON o.user_id = u.id
    //     WHERE o.order_status = 2
    //     OR o.order_status = 3
    //     ORDER BY o.order_id DESC"
    // )->fetchAll();
    
    // Paging (class)
    $page = req('page',1);
    $page = max($page,1);
    
    require_once '../_/lib/Pager.php';
    $p = new Pager('SELECT o.*, u.name 
    FROM orders AS o 
    JOIN user AS u ON o.user_id = u.id
    WHERE o.order_status = 2
    OR o.order_status = 3
    ORDER BY o.order_id DESC',
                    [], 25, $page);
    $arr = $p->result;

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
                <table class="table align-items-center mb-0" id="target">
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
                    <?php foreach($arr as $o):?>
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

    <nav aria-label="Page navigation example">
        <?= $p->html() ?>
    </nav>
<script>
  // TODO: AJAX
    $(document).on('click','.pager a', e=>{
        e.preventDefault();
        // const param = $(e.target).serializeArray();
        // console.log(param);
        $('#target').load(e.target.href + ' #target >');
    });
</script>
<?php 
    include('../_/layout/admin/footer.php');
?>