<?php
include '/_/_base.php';

// ----------------------------------------------------------------------------

auth('Admin');

$role = req('role');

$stm = $db->prepare('SELECT * FROM user WHERE role = ? OR ?');
$stm->execute([$role, $role == null]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'User | Index';
include '/_/_head.php';
?>

<style>
    .photo {
        width: 100px;
        height: 100px;
        border: 1px solid #333;
    }
</style>

<p>
    <button data-get="insert.php">Insert Admin</button>
    <button data-post="restore.php" data-confirm>Restore</button>
</p>

<p>
    <a href="?">All</a> |
    <a href="?role=Admin">Admin</a> |
    <a href="?role=Member">Member</a>
</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Email</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Role</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $user){ 
        $u=get_user($user->id)?>
    <tr>
        <td><?= $u->id ?></td>
        <td><?= $u->email ?></td>
        <td><?= $u->name ?></td>
        <td><?= $u->gender ?></td>
        <td><?= $u->role ?></td>
        <td>
            <!-- Hide for user #1 -->
            <?php if ($u->id != $_SESSION["user"]->id && $u->id != 1 ): ?>
                <button data-post="delete.php?id=<?= $u->id ?>">Delete</button>
            <?php endif ?>

            <img src="/_/photos/profile/<?= $u->photos[0] ?>" class="photo popup">
        </td>
    </tr>
    <?php } ?>
</table>

<?php
include '/_/_foot.php';