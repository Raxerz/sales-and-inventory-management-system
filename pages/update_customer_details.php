<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$name =$_POST['nameEdit'];
	$address = $_POST['addressEdit'];
	$contact = $_POST['contactEdit'];
	$collection = $_POST['collectionEdit'];
	mysqli_query($con,"update customer set cust_first='$name',cust_address='$address',cust_contact='$contact',collection='$collection' where cust_id='$id'")or die(mysqli_error());
	
	echo "<script>document.location='cust_new1.php?success=1'</script>";  

	
?>
