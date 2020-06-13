<?php
include("../dist/includes/dbcon.php");
$id = $_REQUEST['id'];
$cid = $_REQUEST['cid'];

	$daily_transactions = mysqli_query($con, "SELECT * FROM daily_transactions natural join sales_details natural join sales WHERE daily_transaction_id='$id'") or die(mysqli_error($con));
	  while ($row=mysqli_fetch_array($daily_transactions)) {
		  $sales_id = $row['sales_id'];
		  $date = $row['date'];
		  $amount_due = $row['amount_due'];
		  $qty=$row['qty'];
		  $collected = $row['collected_amt'];
		  $pid=$row['prod_id'];
		  if(!in_array($sales_id, $sales_array)){
		  	mysqli_query($con, "UPDATE customer set collection=collection-'$amount_due', collected=collected-'$collected' where cust_id='$cid'") or die(mysqli_error($con)); 
		  	$sales_array[]=$sales_id;
		  }
		  mysqli_query($con,"UPDATE product SET prod_qty=prod_qty+'$qty' where prod_id='$pid'") or die(mysqli_error($con)); 

		  $daily_stockout_exist = mysqli_query($con, "SELECT * FROM stockout WHERE date='$date' and prod_id='$pid' LIMIT 1");
		  $num_rows = mysqli_num_rows($daily_stockout_exist);
			if ($num_rows > 0) {
			  	mysqli_query($con, "UPDATE stockout set qty=qty-'$qty' where prod_id='$pid' and date='$date'") or die(mysqli_error($con));
			}

		  $result=mysqli_query($con,"DELETE FROM sales where sales_id='$sales_id'");
		}

    $result=mysqli_query($con,"DELETE FROM daily_transactions where daily_transaction_id='$id'");
	
echo "<script>document.location='cust_new.php'</script>";  
?>
