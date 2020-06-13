<?php 
session_start();
$id=$_SESSION['id'];
$branch=$_SESSION['branch'];	

include('../dist/includes/dbcon.php');

        $branch=$_SESSION['branch'];
        $cid=$_REQUEST['cid'];
        $date=$_REQUEST['date'];
        $did=$_REQUEST['did'];

        $result=mysqli_query($con,"DELETE FROM temp_trans")
		or die(mysqli_error());
		
		$query=mysqli_query($con,"select * from daily_transactions natural join sales_details natural join product where branch_id='$branch' and cust_id='$cid' and date='$date'")or die(mysqli_error());
		while($row=mysqli_fetch_array($query)){
			$name = $row['prod_id'];
			$qty = $row['qty'];
			$price = $row['price'];
			$discount = $row['discount'];
			
			$query2=mysqli_query($con,"select * from temp_trans where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
			$count=mysqli_num_rows($query2);
			
			$total=$price*$qty;
			$total=$total - $discount;
			
			if ($count>0){
				mysqli_query($con,"update temp_trans set qty=qty+'$qty',price=price+'$total' where prod_id='$name' and branch_id='$branch'")or die(mysqli_error());
		
			}
			else{
				mysqli_query($con,"INSERT INTO temp_trans(prod_id,qty,price,discount,branch_id) VALUES('$name','$qty','$price','$discount','$branch')")or die(mysqli_error($con));
			}
		}
	
		echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did'</script>";  
	
?>