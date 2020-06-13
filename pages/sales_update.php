<?php 
session_start();
$id=$_SESSION['id'];	
$cid=$_REQUEST['cid'];
$branch=$_SESSION['branch'];
include('../dist/includes/dbcon.php');

	$did=$_REQUEST['did'];
	$date=date("Y-m-d");
	$daily_transactions = mysqli_query($con, "SELECT * FROM daily_transactions natural join sales_details natural join sales WHERE daily_transaction_id='$did'") or die(mysqli_error($con));
	  while ($row=mysqli_fetch_array($daily_transactions)) {
	  	  GLOBAL $date;
		  $sales_id = $row['sales_id'];
		  $date = $row['date'];
		  $amount_due = $row['amount_due'];
		  $qty=$row['qty'];
		  $collected = $row['collected_amt'];
		  $pid=$row['prod_id'];
		  if(!in_array($sales_id, $sales_array)){
		  	mysqli_query($con, "UPDATE customer set collection=collection-'$amount_due', collected=collected-'$collected' where cust_id='$cid' and branch_id='$branch'") or die(mysqli_error($con)); 
		  	$sales_array[]=$sales_id;
		  }
		  mysqli_query($con,"UPDATE product SET prod_qty=prod_qty+'$qty' where prod_id='$pid' and branch_id='$branch'") or die(mysqli_error($con)); 

		  $daily_stockout_exist = mysqli_query($con, "SELECT * FROM stockout WHERE date='$date' and prod_id='$pid' LIMIT 1");
		  $num_rows = mysqli_num_rows($daily_stockout_exist);
			if ($num_rows > 0) {
			  	mysqli_query($con, "UPDATE stockout set qty=qty-'$qty' where prod_id='$pid' and date='$date'") or die(mysqli_error($con));
			}

		  $result=mysqli_query($con,"DELETE FROM sales where sales_id='$sales_id'");
		}

    $result=mysqli_query($con,"DELETE FROM daily_transactions where daily_transaction_id='$did'");

	$amount_due = $_POST['amount_due'];
    $returned = $_POST['returned'];
	
	$total=$amount_due;

		$tendered = $_POST['tendered'];
		$change = $_POST['change'];
		$collected = $tendered+($change-$returned);

		mysqli_query($con,"INSERT INTO sales(cust_id,user_id,amount_due,total,date_added,modeofpayment,cash_tendered,cash_change,branch_id, collected_amt) 
	VALUES('$cid','$id','$amount_due','$total','$date','cash','$tendered','$change','$branch','$collected')")or die(mysqli_error($con));
 
	$sales_id=mysqli_insert_id($con);
	$_SESSION['sid']=$sales_id;
	$daily_transact_exist = mysqli_query($con, "SELECT * FROM daily_transactions WHERE date='$date' and cust_id='$cid' LIMIT 1");
	$num_rows = mysqli_num_rows($daily_transact_exist);
	if ($num_rows > 0) {
	  mysqli_query($con, "UPDATE daily_transactions set daily_collected=daily_collected+'$collected'") or die(mysqli_error($con));
	  while ($row=mysqli_fetch_array($daily_transact_exist)) {
		  $daily_transact_id = $row['daily_transaction_id'];
		}
	}
	else {
	  	mysqli_query($con, "INSERT INTO daily_transactions (date, cust_id, daily_collected, branch_id) VALUES('$date', '$cid', '$collected',  $branch)") or die(mysqli_error($con));
		$daily_transact_id=mysqli_insert_id($con);
	}
	$query=mysqli_query($con,"select * from temp_trans where branch_id='$branch'")or die(mysqli_error($con));
		while ($row=mysqli_fetch_array($query))
		{
			$pid=$row['prod_id'];	
 			$qty=$row['qty'];
			$price=$row['price'];
			$discount=$row['discount'];
			
			mysqli_query($con,"INSERT INTO sales_details(prod_id,qty,price,sales_id,discount,daily_transaction_id) VALUES('$pid','$qty','$price','$sales_id','$discount','$daily_transact_id')")or die(mysqli_error($con));
			mysqli_query($con,"UPDATE product SET prod_qty=prod_qty-'$qty' where prod_id='$pid' and branch_id='$branch'") or die(mysqli_error($con)); 
			$daily_stockout_exist = mysqli_query($con, "SELECT * FROM stockout WHERE date='$date_added' and prod_id='$pid' LIMIT 1");
			$num_rows = mysqli_num_rows($daily_stockout_exist);
			if ($num_rows > 0) {
			  	mysqli_query($con, "UPDATE stockout set qty=qty+'$qty' where prod_id='$pid' and date='$date_added'") or die(mysqli_error($con));
			}
			else {
			    mysqli_query($con,"INSERT INTO stockout(prod_id,qty,date, sell_price, branch_id) VALUES('$pid','$qty','$date','$price','$branch')")or die(mysqli_error($con));
			}
		}
		

		mysqli_query($con, "UPDATE customer set collection=collection+'$tendered', collected=collected+'$collected' where cust_id='$cid' and branch_id='$branch'") or die(mysqli_error($con)); 

		$query1=mysqli_query($con,"SELECT or_no FROM payment NATURAL JOIN sales WHERE modeofpayment =  'cash' ORDER BY payment_id DESC LIMIT 0 , 1")or die(mysqli_error($con));

			$row1=mysqli_fetch_array($query1);
				$or=$row1['or_no'];	

				if ($or==0)
				{
					$or=1901;
				}
				else
				{
					$or=$or+1;
				}

				mysqli_query($con,"INSERT INTO payment(cust_id,user_id,payment,payment_date,branch_id,payment_for,due,status,sales_id,or_no) 
	VALUES('$cid','$id','$total','$date','$branch','$date','$total','paid','$sales_id','$or')")or die(mysqli_error($con));
				echo "<script>document.location='receipt.php?cid=$cid'</script>";  	
		
		$result=mysqli_query($con,"DELETE FROM temp_trans where branch_id='$branch'")	or die(mysqli_error($con));
		//echo "<script>document.location='receipt.php?cid=$cid'</script>";  	
		
	
?>
