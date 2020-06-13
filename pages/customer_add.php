<?php 
session_start();
include('../dist/includes/dbcon.php');
	$branch=$_SESSION['branch'];
	$name = $_POST['first'];
	$address = $_POST['address'];
	$contact = $_POST['contact'];
	$collection = $_POST['collection'];
	
	$query2=mysqli_query($con,"select * from customer where cust_first='$name' and cust_address='$address' and branch_id='$branch'")or die(mysqli_error($con));
		$count=mysqli_num_rows($query2);

		if ($count>0)
		{
			// echo "<script type='text/javascript'>alert('Customer already exist!');</script>";
			echo "<script>document.location='cust_new1.php?error=1'</script>";  
		}
		else
		{	
			
			mysqli_query($con,"INSERT INTO customer(cust_first,cust_address,cust_contact,branch_id,balance,cust_pic,collection) 
				VALUES('$name','$address','$contact','$branch','0','default.gif','$collection')")or die(mysqli_error($con));

			$id=mysqli_insert_id($con);
			$_SESSION['cid']=$id;
			//echo "<script type='text/javascript'>alert('Successfully added new customer!');</script>";
			mysqli_query($con,"DELETE from temp_trans")or die(mysqli_error($con));
			echo "<script>document.location='cash_transaction.php?cid=$id?success=1'</script>";  
		}
?>
