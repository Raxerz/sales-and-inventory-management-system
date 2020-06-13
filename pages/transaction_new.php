<?php 
session_start();
$id=$_SESSION['id'];
$branch=$_SESSION['branch'];	

include('../dist/includes/dbcon.php');

$cid=$_REQUEST['cid'];

$result=mysqli_query($con,"DELETE FROM temp_trans")
		or die(mysqli_error());
echo "<script>document.location='cash_transaction.php?cid=$cid'</script>"; 

?>