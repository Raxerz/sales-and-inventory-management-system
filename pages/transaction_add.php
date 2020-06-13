<?php 
session_start();
$id=$_SESSION['id'];
$branch=$_SESSION['branch'];	

include('../dist/includes/dbcon.php');

	$cid = $_POST['cid'];
	$name = $_POST['prod_name'];
	$rate = $_POST['rate'];
	$price=$rate;
	$discount = $_POST['discount'];
	$qty = $_POST['qty'];
	$isNew = $_POST['new'];
		
			
		$query=mysqli_query($con,"select prod_id,prod_qty from product where prod_id='$name'")or die(mysqli_error());
		$row=mysqli_fetch_array($query);
		
		$prod_qty=$row['prod_qty'];
		
		$query1=mysqli_query($con,"select * from temp_trans where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
		$count=mysqli_num_rows($query1);
		$row1=mysqli_fetch_array($query1);
		$old_qty=$row1['qty'];

		
		if ($count>0){
			echo "here";
			if(($old_qty+$qty)<=$prod_qty){
				//mysqli_query($con,"update product set prod_qty=prod_qty-'$qty' where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
				mysqli_query($con,"update temp_trans set qty=qty+'$qty' where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
			} else {
			if($isNew==1){
		echo "<script>document.location='cash_transaction.php?cid=$cid&error=1'</script>";  
	} else {
		$did = $_POST['did'];
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did&error=1'</script>";
	}				
			}
		}
		else{
			if($qty<=$prod_qty){
				//mysqli_query($con,"update product set prod_qty=prod_qty-'$qty' where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
				mysqli_query($con,"INSERT INTO temp_trans(prod_id,qty,price,discount,branch_id) VALUES('$name','$qty','$price','$discount','$branch')")or die(mysqli_error());
			} else {
							if($isNew==1){
		echo "<script>document.location='cash_transaction.php?cid=$cid&error=1'</script>";  
	} else {
		$did = $_POST['did'];
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did&error=1'</script>";
	}
			}
		}
			if($isNew==1){
		echo "<script>document.location='cash_transaction.php?cid=$cid&success=1'</script>";  
	} else {
		$did = $_POST['did'];
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did&success=1'</script>";
	}
	
?>