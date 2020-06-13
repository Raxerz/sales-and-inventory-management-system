<?php
session_start();
include("../dist/includes/dbcon.php");
$user_id=$_SESSION['id'];
$id = $_REQUEST['id'];
$qty = $_REQUEST['qty'];
$pid = $_REQUEST['pid'];
$date = $_REQUEST['date'];

$result=mysqli_query($con,"DELETE FROM stockout WHERE stockout_id ='$id'")
	or die(mysqli_error());
mysqli_query($con,"UPDATE product SET prod_qty=prod_qty+'$qty' where prod_id='$pid'") or die(mysqli_error($con));

/*	$daily_transactions = mysqli_query($con, "SELECT * FROM daily_transactions natural join sales_details natural join sales WHERE date='$date' and prod_id='$pid'") or die(mysqli_error($con));
	  while ($row=mysqli_fetch_array($daily_transactions)) {
	  	  $sales_details_id = $row['sales_details_id'];
		  if(!in_array($sales_details_id, $sales_array)){
		  	$total= $row['price'] * $row['qty'];
		  	mysqli_query($con, "UPDATE customer set collection=collection-'$total', collected=collected-'$collected' where cust_id='$cid' and branch_id='$branch'") or die(mysqli_error($con)); 
		  	$sales_array[]=$sales_details_id;
		  }
		  $result=mysqli_query($con,"DELETE FROM sales_details where sales_details_id='$sales_details_id'");
		}*/

		$query=mysqli_query($con,"select prod_name,prod_qty from product where prod_id='$pid'")or die(mysqli_error());
			$row=mysqli_fetch_array($query);
			$name=$row['prod_name'];
			$unit=$row['prod_unit'];
			date_default_timezone_set("Asia/Calcutta"); 
			$date = date("Y-m-d H:i:s");
	
	$remarks="deleted $qty $unit of $name from stockout";
mysqli_query($con,"INSERT INTO history_log(user_id,action,date) VALUES('$user_id','$remarks','$date')")or die(mysqli_error($con));

echo "<script>document.location='stockout.php'</script>";  
?>