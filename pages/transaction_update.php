<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$qty =$_POST['qty3'];
	$rate =$_POST['rateEdit'];
	$discount =$_POST['discEdit'];
	$cid =$_POST['cid'];
	$isNew = $_POST['new'];
	
	$query1=mysqli_query($con,"select * from temp_trans where temp_trans_id='$id'")or die(mysqli_error());
	$row1=mysqli_fetch_array($query1);
	$old_qty=$row1['qty'];
	$pid=$row1['prod_id'];

			$query=mysqli_query($con,"select prod_price,prod_id,prod_qty from product where prod_id='$pid'")or die(mysqli_error());
		$row=mysqli_fetch_array($query);
		$prod_qty=$row['prod_qty'];
	if($qty<=$prod_qty){
	if($qty==0){
		//mysqli_query($con,"update product set prod_qty=prod_qty+'$old_qty' where prod_id='$pid'")or die(mysqli_error());
		$result=mysqli_query($con,"DELETE FROM temp_trans WHERE temp_trans_id ='$id'")
	or die(mysqli_error());
	} else if($old_qty<$qty){
		$diff = $qty - $old_qty;
		//mysqli_query($con,"update product set prod_qty=prod_qty-'$diff' where prod_id='$pid'")or die(mysqli_error());		
		mysqli_query($con,"update temp_trans set qty='$qty',discount='$discount',price='$rate' where temp_trans_id='$id'")or die(mysqli_error());
	} else if($old_qty>$qty){
		$diff = $old_qty - $qty;
		//mysqli_query($con,"update product set prod_qty=prod_qty+'$diff' where prod_id='$pid'")or die(mysqli_error());		
		mysqli_query($con,"update temp_trans set qty='$qty',discount='$discount',price='$rate' where temp_trans_id='$id'")or die(mysqli_error());
	} else {
		mysqli_query($con,"update temp_trans set discount='$discount',price='$rate' where temp_trans_id='$id'")or die(mysqli_error());
	}

	if($isNew==1){
		echo "<script>document.location='cash_transaction.php?cid=$cid'</script>";  
	} else {
		$did = $_POST['did'];
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did'</script>";
	}
	} else {
	if($isNew==1){
		echo "<script>document.location='cash_transaction.php?cid=$cid&error=1'</script>";  
	} else {
		$did = $_POST['did'];
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did&error=1'</script>";
	}		
	}


	
?>
