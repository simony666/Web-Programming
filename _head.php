<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="shortcut icon" href="/_/images/favicon.png">
    <link rel="stylesheet" href="/_/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/_/js/app.js"></script>
    <?= $_head ?>
</head>
<body>
    <div id="info"><?= temp('info') ?></div>
    
    <header>
        <h1><a href="/">Web Programming</a></h1>

        <?php if ($user): ?>
            <div>
                <?= $user->name ?><br>
                <?= $user->role ?>
            </div>
        
            <img src="/_/photos/<?= $user->photo ?>">
        <?php endif ?>
    </header>

    <nav>
        <a href="/">Index</a>

        <?php if ($user?->role == 'Admin'): ?>
            <a href="/user/index.php">User</a>
        <?php endif ?>

        <div></div>

        <?php if ($user): ?>
            <a href="/user/profile.php">Profile</a>
            <a href="/user/password.php">Password</a>
            <a href="/logout.php">Logout</a>
        <?php else: ?>
            <a href="/register.php">Register</a>
            <a href="/user/reset.php">Reset Password</a>
            <a href="/login.php">Login</a>
        <?php endif ?>
    </nav>

    <main>
        <h1><?= $_title ?></h1>