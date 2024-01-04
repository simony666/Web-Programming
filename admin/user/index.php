<?php
include '../../_/_base.php';
auth('Admin');

// ----------------------------------------------------------------------------

$arr = $db->query('SELECT * FROM user')->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'User | Index';
include('../../_/layout/admin/header.php');
?>

<form method="post">
    <?php if ($user?->role == 'Admin'): ?>
        <p>
            <button data-get="./insert.php">Insert</button>
        </p>
    <?php endif ?>
</form>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Gender</th>
        <th>Status</th>
    </tr>

    <?php foreach ($arr as $u): ?>
    <tr class="user">
        <td><?= $u->name ?></td>
        <td><?= $u->email ?></td>
        <td><?= $u->role ?></td>
        <td><?= $u->gender ?></td>
        <td><?= $u->status ?></td>
        <td>
            <form method="post">
                <?php if ($user?->role == 'Admin'): ?>
                    <button data-post="delete.php?id=<?= $u->id ?>" data-confirm>Delete</button>
                <?php endif ?>
            </form>
        </td>
    </tr>
    <?php endforeach ?>
</table>



<?php
include('../../_/layout/admin/footer.php');