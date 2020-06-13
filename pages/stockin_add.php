<?php 
session_start();
include('../dist/includes/dbcon.php');
	$branch=$_SESSION['branch'];
	$name = $_POST['prod_name'];
	$qty = $_POST['qty'];
	
	date_default_timezone_set('Asia/Calcutta');

	$date = $_POST['date'];
	$id=$_SESSION['id'];
	
	$query=mysqli_query($con,"select prod_name from product where prod_id='$name'")or die(mysqli_error());
  
    $row=mysqli_fetch_array($query);
	$product=$row['prod_name'];
	$prod_qty = $row['prod_qty'];
	$remarks="added $qty of $product";  
	
	mysqli_query($con,"INSERT INTO history_log(user_id,action,date) VALUES('$id','$remarks','$date')")or die(mysqli_error($con));
	mysqli_query($con,"UPDATE product SET prod_qty=prod_qty+'$qty',main_qty=main_qty+'$qty' where prod_id='$name' and branch_id='$branch'") or die(mysqli_error($con));
			
	$daily_stockin_exist = mysqli_query($con, "SELECT * FROM stockin WHERE date='$date' and prod_id='$name' LIMIT 1");
	$num_rows = mysqli_num_rows($daily_stockin_exist);
	if ($num_rows > 0) {
	  mysqli_query($con, "UPDATE stockin set qty=qty+'$qty' where prod_id='$name' and date='$date'") or die(mysqli_error($con));
	}
	else {
			mysqli_query($con,"INSERT INTO stockin(prod_id,qty,date, branch_id) VALUES('$name','$qty','$date','$branch')")or die(mysqli_error($con));
	}
	echo "<script>document.location='stockin.php?success=1'</script>";  
	
?>
