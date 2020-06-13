<?php
include('dbcon.php');

 if (isset($_POST['update']))
 { 
	 $id = $_POST['branch_id'];
	 $branch_name = $_POST['branch_name'];
	 $branch_address = $_POST['branch_address'];
	 $branch_contact = $_POST['branch_contact'];
	 $skin = $_POST['skin'];

	$bg="";
		foreach($_FILES['image']['tmp_name'] as $key => $tmp_name ){
			
			$file_tmp =$_FILES['image']['tmp_name'][$key];
			$type   = exif_imagetype($_FILES['image']['tmp_name'][$key]);
			if($type==IMAGETYPE_JPEG){
				$bg = "bg.jpg";
				move_uploaded_file($file_tmp,"../../images/".$bg);			
			} else if($type==IMAGETYPE_PNG){
				$bg = "bg.png";
				move_uploaded_file($file_tmp,"../../images/".$bg);				
			}
		}

	

	 mysqli_query($con,"UPDATE branch SET branch_name='$branch_name', branch_address = '$branch_address', branch_contact = '$branch_contact', skin = '$skin', bg='$bg' where branch_id='$id'")
	 or die(mysqli_error($con)); 

		//echo "<script type='text/javascript'>alert('Successfully updated Branch details!');</script>";
		echo "<script>document.location='branch.php?success=1'</script>";
	
} 

