<?php session_start();

include('dbcon.php');

if(isset($_POST['login']))
{

$user_unsafe=$_POST['username'];
$pass_unsafe=$_POST['password'];

$user = mysqli_real_escape_string($con,$user_unsafe);
$pass1 = mysqli_real_escape_string($con,$pass_unsafe);
$pass=md5($pass1);
$salt="a1Bz20ydqelm8m1wql";
$pass=$salt.$pass;

$query=mysqli_query($con,"select * from user where username='$user' and password='$pass' and status = 'Active'")or die(mysqli_error($con));
		$row=mysqli_fetch_array($query);
           $id=$row['user_id'];
          /*  $first=$row['admin_first'];
           $last=$row['admin_last']; */
           $counter=mysqli_num_rows($query);

		  	if ($counter == 0) 
			  {	
				  echo "<script type='text/javascript'>document.location='index.php?error=1'</script>";
			  } 
			  else
			  {
				  $_SESSION['id']=$id;	
				  /* $_SESSION['name']=$first." ".$last; */

			  	echo "<script type='text/javascript'>document.location='reports.php'</script>";  
			  }
}
?>