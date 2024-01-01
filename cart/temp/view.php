<?php
include '../../_base.php';

// ----------------------------------------------------------------------------
// delete photos
if (is_post()) {
    $photos = req('product_image',[]);
    
    $n = 0;
    foreach($photos as $photo){
    // ensure that only filename return
        $photo = basename($photo);    
        unlink("../../_/photos/$photo");
        $n++;
    }
    
    
    temp('info', "$n photo(s) deleted");
    redirect(); // 每次post 都要 redirect
}

// show all the save image from photos folder
$arr = glob('../../_/photos/*.jpg');
// get the file name, instead of path name
$arr = array_map('basename',$arr);

// ----------------------------------------------------------------------------

$_title = 'Browse';
include '../../_head.php';
?>

<style>
    .photos{
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .photos label{
        position: relative;
    }

    .photos img{
        border: 1px solid #333;
        width: 200px;
        height: 200px;
        cursor: pointer;
    }

    .photos input{
        position: absolute;
        left: 0;
    }

    .photos button{
        position: absolute;
        right: 0;
    }

    .photos label:has(:checked) img{
        outline: 3px solid red;
    }
</style>

<form method="post" id="f">
    <button data-check="photos[]">Check All</button>
    <button data-uncheck="photos[]">Uncheck All</button>
    <button>Delete Checked</button>
</form>

<p><?= count($arr) ?> photo(s)</p>

<div class="photos">
    <?php foreach ($arr as $f): ?>
    <label>
        <img src="../../_/photos/<?= $f ?>" alt="">
        <input type="checkbox" name="photos[]" value="<?= $f ?>" form="f">
        <button data-post="?photos[]=<?= $f ?>">X</button>
    </label>
    <?php endforeach ?>
</div>

<?php
include '../../_foot.php';