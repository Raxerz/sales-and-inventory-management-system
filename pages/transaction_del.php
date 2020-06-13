<?php
include("../dist/includes/dbcon.php");
$id = $_REQUEST['id'];
$cid = $_POST['cid'];
$isNew = $_POST['new'];
$query1=mysqli_query($con,"select * from temp_trans where temp_trans_id='$id'")or die(mysqli_error());
$row1=mysqli_fetch_array($query1);
$old_qty=$row1['qty'];
$pid=$row1['prod_id'];
//mysqli_query($con,"update product set prod_qty=prod_qty+'$old_qty' where prod_id='$pid'")or die(mysqli_error());

$result=mysqli_query($con,"DELETE FROM temp_trans WHERE temp_trans_id ='$id'")
	or die(mysqli_error());
	
if($isNew==1){
	echo "<script>document.location='cash_transaction.php?cid=$cid'</script>";  
} else {
	$did = $_POST['did'];
	echo "<script>document.location='update_cash_transaction.php?cid=$cid&did=$did'</script>";
}
?>
