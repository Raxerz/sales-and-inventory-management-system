<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$name =$_POST['name'];
	$address = $_POST['address'];
	$contact = $_POST['contact'];
	$collection = $_POST['collection'];
	$collected = $_POST['collected'];
	$balance = $collection-$collected;
	mysqli_query($con,"update customer set cust_first='$name',cust_address='$address',cust_contact='$contact',collection='$collection',collected='$collected',balance='$balance' where cust_id='$id'")or die(mysqli_error());
	
	// echo "<script type='text/javascript'>alert('Successfully updated customer details!');</script>";
	echo "<script>document.location='customer.php?success=1'</script>";  

	
?>
