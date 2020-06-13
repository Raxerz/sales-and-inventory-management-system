<?php 
session_start();
include('../dist/includes/dbcon.php');
	$branch=$_SESSION['branch'];
	$name = $_POST['prod_name'];
	$qty = $_POST['prod_qty'];
	$price = $_POST['prod_price'];
	date_default_timezone_set("Asia/Calcutta"); 
	$date = $_POST['date'];
	$id=$_SESSION['id'];

	$query=mysqli_query($con,"select * from product where prod_id='$name'")or die(mysqli_error());
  
        $row=mysqli_fetch_array($query);
		$product=$row['prod_name'];
		$product_qty=$row['prod_qty'];
	$remarks="sold $qty of $product"; 
	if($product_qty>=$qty){

		mysqli_query($con,"INSERT INTO history_log(user_id,action,date) VALUES('$id','$remarks','$date')")or die(mysqli_error($con));
			
	mysqli_query($con,"UPDATE product SET prod_qty=prod_qty-'$qty' where prod_id='$name' and branch_id='$branch'") or die(mysqli_error($con));

	$daily_stockout_exist = mysqli_query($con, "SELECT * FROM stockout WHERE date='$date' and prod_id='$name' LIMIT 1");
	$num_rows = mysqli_num_rows($daily_stockout_exist);
	if ($num_rows > 0) {
	  mysqli_query($con, "UPDATE stockout set qty=qty+'$qty' where prod_id='$name'") or die(mysqli_error($con));
	}
	else {
			mysqli_query($con,"INSERT INTO stockout(prod_id,qty,date, sell_price, branch_id) VALUES('$name','$qty','$date','$price','$branch')")or die(mysqli_error($con));
	}
	echo "<script>document.location='stockout.php?success=1'</script>";  
} else {
	echo "<script>document.location='stockout.php?error=1'</script>";  
}
	
?>
