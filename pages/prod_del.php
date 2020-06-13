<?php
session_start();
include("../dist/includes/dbcon.php");
$user_id=$_SESSION['id'];
$pid = $_REQUEST['pid'];

mysqli_query($con,"UPDATE product set is_deleted=1 where prod_id='$pid'")or die(mysqli_error($con));
	

echo "<script>document.location='product.php?success=3'</script>";  
?>
