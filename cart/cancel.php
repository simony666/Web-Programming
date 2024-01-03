<?php
include('../_/_base.php');

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Order | Cancel';
include('../_/layout/customer/_head.php');
?>


<script>
    alert("Checkout canceled, back to cart page");
    window.location = "cart.php";
</script>

<?php include('../_/layout/customer/_foot.php'); ?>