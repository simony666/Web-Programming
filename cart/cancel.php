<?php
include('../_base.php');

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Order | Cancel';
include('../_/customerLayout/_head.php');
?>


<script>
    alert("Checkout canceled, back to cart page");
    window.location = "cart.php";
</script>

<?php include('../_/customerLayout/_foot.php'); ?>