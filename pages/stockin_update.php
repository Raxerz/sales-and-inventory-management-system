<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
	$branch=$_SESSION['branch'];
	$id = $_POST['id'];
	$new_qty =$_POST['qty'];
	$prod_id =$_POST['prod_id'];
	$date =$_POST['date_update'];
	
	$query=mysqli_query($con,"select * from stockin where stockin_id='$id'")or die(mysqli_error());
	
        $row=mysqli_fetch_array($query);


	$old_qty = $row['qty'];
	if($old_qty<$new_qty) {
		$diff = $new_qty - $old_qty;
		mysqli_query($con,"update product set prod_qty=prod_qty +'$diff',main_qty=main_qty +'$diff' where prod_id='$prod_id' and branch_id='$branch'")or die(mysqli_error());
	} else {
		$diff =  $old_qty - $new_qty;
		mysqli_query($con,"update product set prod_qty=prod_qty-'$diff',main_qty=main_qty +'$diff' where prod_id='$prod_id' and branch_id='$branch'")or die(mysqli_error());	
	}
	$daily_stockin_exist = mysqli_query($con, "SELECT * FROM stockin WHERE date='$date' and prod_id='$prod_id' and stockin_id!='$id' LIMIT 1");
	$num_rows = mysqli_num_rows($daily_stockin_exist);
	if ($num_rows > 0) {
	  mysqli_query($con, "UPDATE stockin set qty=qty+'$new_qty' where date='$date' and prod_id='$prod_id'") or die(mysqli_error($con));
	  mysqli_query($con, "DELETE FROM stockin where stockin_id='$id'") or die(mysqli_error($con));
	}
	else {
		mysqli_query($con,"update stockin set qty='$new_qty', date='$date' where stockin_id='$id'")or die(mysqli_error());
	}

	echo "<script>document.location='stockin.php?success=1'</script>";  

	
?>
