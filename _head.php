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
    </nav>

    <main>
        <h1><?= $_title ?></h1>