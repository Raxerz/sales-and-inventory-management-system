<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer | <?php include('../dist/includes/title.php');?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../plugins/select2/select2.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>    
    <style>
      
    </style>
 </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-<?php echo $_SESSION['skin'];?> layout-top-nav">
  <?php if($_REQUEST['success']==1){
      ?>
      <script type='text/javascript'>swal('Success!','Successfully updated customer details!','success');</script>
    <?php 
  } 
  ?>    
    <div class="wrapper">
      <?php include('../dist/includes/header.php');
      include('../dist/includes/dbcon.php');
      ?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <div class="container">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>
              <a class="btn btn-lg btn-warning" href="home.php">Back</a>
              
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Customer</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <div class="row">
	          
			
            <div class="col-xs-12">
              <div class="box box-primary">
    
                <div class="box-header">
                  <h3 class="box-title">Customer List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
            		<th>Sl No.</th>
                        <th>Customer Name</th>
                        <th>Address</th>
            		<th>Contact Number</th>
			<th>Collection</th>
			<th>Collected</th>
            		<th>Balance</th>
			<th>Status</th>
                        <th>Action</th>
						
                      </tr>
                    </thead>
                    <tbody>
		<?php

			$query=mysqli_query($con,"select * from customer natural join sales where branch_id='$branch'")or die(mysqli_error());
			$i=1;
			while($row=mysqli_fetch_array($query)){
				$cid=$row['cust_id'];
				$collected_arr[$cid] += $row['cash_tendered'];
			}
			$branch=$_SESSION['branch'];
			$query=mysqli_query($con,"select * from customer where branch_id='$branch'")or die(mysqli_error());
			$i=1;
			while($row=mysqli_fetch_array($query)){
				$cid=$row['cust_id'];
		?>
                      <tr>
			<td><?php echo $row['cust_id'];?></td>
                        <td><?php echo $row['cust_first'];?></td>
                       	<td><?php echo $row['cust_address'];?></td>
			<td><?php echo $row['cust_contact'];?></td>
			<td><?php echo $row['collection'];?></td>
			<td><?php echo $row['collected'];?></td>
			<td><?php 
				$balance = $row['collection'] - $row['collected'];
				echo number_format($balance,2);?>
			</td>
			<td><?php if ($balance==0) 
					echo "<span class='label label-success'>settled</span>";
				  else if($balance<0) 
					echo "<span class='label label-warning'>borrowed</span>";
				  else 
					echo "<span class='label label-danger'>pending</span>";
			?></td>
                        <td>
				<a href="#updateordinance<?php echo $row['cust_id'];?>" data-target="#updateordinance<?php echo $row['cust_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-edit text-blue"></i></a>
				
						</td>
                      </tr>
				<div id="updateordinance<?php echo $row['cust_id'];?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	  <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Customer Details</h4>
              </div>
              <div class="modal-body">
			  <form class="form-horizontal" method="post" action="customer_update.php" enctype='multipart/form-data'>
                		<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['cust_id'];?>" required>  
                		<input type="hidden" class="form-control" id="name" name="name" value="<?php echo $row['cust_first'];?>" required>  
				<input type="hidden" class="form-control" id="address" name="address" value="<?php echo $row['cust_address'];?>" required>  
				<input type="hidden" class="form-control" id="contact" name="contact" value="<?php echo $row['cust_contact'];?>" required>  
				<div class="form-group">
					<label class="control-label col-lg-3" for="price">Collection</label>
					<div class="col-lg-9">
					  <input type="number" step=".01" min="0" class="form-control" id="price" name="collection" value="<?php echo $row['collection'];?>" required>  
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3" for="price">Collected</label>
					<div class="col-lg-9">
					  <input type="number" step=".01" min="0" class="form-control" id="price" name="collected" value="<?php echo $row['collected'];?>" required>  
					</div>
				</div>
				
              </div><br><br><br><hr>
              <div class="modal-footer">
		<button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
			
        </div><!--end of modal-dialog-->
 </div>
 <!--end of modal-->   	  
                 
<?php $i++;}?>					  
                    </tbody>
                    <tfoot>
                      <tr>
            		<th>Sl No.</th>
                        <th>Customer Name</th>
                        <th>Address</th>
            		<th>Contact Number</th>
			<th>Collection</th>
			<th>Collected</th>
            		<th>Balance</th>
			<th>Status</th>
                        <th>Action</th>
                      </tr>					  
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
 
            </div><!-- /.col -->
			
			
          </div><!-- /.row -->
	 
            
          </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include('../dist/includes/footer.php');?>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/select2/select2.full.min.js"></script>
    <!-- SlimScroll -->
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
    
    <script>
      $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
    </script>
     <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        
        $("input[type='number']").on('input', function () {
          var num = this.value.match(/^\d+$/);
          if (num === null) {
            // If we have no match, value will be empty.
            this.value = "";
          }
        });

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
      });
    </script>
  </body>
</html>
