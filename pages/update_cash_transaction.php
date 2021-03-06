<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if(empty($_SESSION['branch'])):
header('Location:../index.php');
endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home | <?php include('../dist/includes/title.php');?></title>
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
    <script src="../dist/js/jquery.min.js"></script>
    <script language="JavaScript"><!--
javascript:window.history.forward(1);
//--></script>
 </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-<?php echo $_SESSION['skin'];?> layout-top-nav" onload="myFunction()">
      <?php if($_REQUEST['success']==1){
      ?>
      <script type='text/javascript'>swal('Success!','Successfully added product!','success');</script>
    <?php } else if($_REQUEST['error']==1){
      ?>
      <script type='text/javascript'>swal('Error!','Not enough stocks for this product','error');</script>
<?php }      
  ?>  
    <div class="wrapper">
      <?php include('../dist/includes/header.php');?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <div class="container">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>
              <a class="btn btn-lg btn-warning" href="cust_new.php">Back</a>
              
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Product</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <div class="row">
	      <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Sales Transaction</h3>
                </div>
                <div class="box-body">
                  <!-- Date range -->
                  <form method="post" action="transaction_add.php">
				  <div class="row" style="min-height:400px">
					
					 <div class="col-md-6">
						  <div class="form-group">
							<label for="date">Product Name</label>
							 
								<select class="form-control select2" name="prod_name" tabindex="1" autofocus required>
								<?php
                  $branch=$_SESSION['branch'];
                  $cid=$_REQUEST['cid'];
                  $did=$_REQUEST['did'];
								  include('../dist/includes/dbcon.php');
									 $query2=mysqli_query($con,"select * from product where branch_id='$branch' and is_deleted=0 order by prod_name")or die(mysqli_error());
									    while($row=mysqli_fetch_array($query2)){
								?>
										<option value="<?php echo $row['prod_id'];?>"><?php echo $row['prod_name']." Available(".$row['prod_qty'].")";?></option>
								  <?php }?>
								</select>
						    <input type="hidden" class="form-control" name="cid" value="<?php echo $cid;?>" required> 
                <input type="hidden" class="form-control" name="new" value="0" required>   
                <input type="hidden" class="form-control" name="did" value="<?php echo $did;?>" required>    
						  </div><!-- /.form group -->
					</div>
					<div class=" col-md-2">
						<div class="form-group">
							<label for="date">Quantity</label>
							<div class="input-group">
							  <input type="number" min="1" class="form-control pull-right" id="qty1" name="qty" placeholder="Quantity" tabindex="2" value="1"  required>
							</div><!-- /.input group -->
						</div><!-- /.form group -->
					 </div>
           <div class="col-md-6">
              <div class="form-group">
                <label for="date">Selling Rate</label>
                <input type="number" step=".01" min="0" class="form-control" id="rate" name="rate" placeholder="0.0" tabindex="5">
              </div><!-- /.form group -->
          </div>
          <div class=" col-md-2">
            <div class="form-group">
              <label for="date">Discount</label>
              <div class="input-group">
                <input type="number" step=".01" min="0" class="form-control" id="discount" name="discount" placeholder="0.0" tabindex="5">
            </div><!-- /.input group -->
            </div><!-- /.form group -->
           </div>           

					<div class="col-md-2">
						<div class="form-group">
							<label for="date"></label>
							<div class="input-group">
								<button class="btn btn-lg btn-primary" type="submit" tabindex="3" name="addtocart">+</button>
							</div>
						</div>	
					</form>	
					</div>
					<div class="col-md-12">
<?php 
$queryb=mysqli_query($con,"select balance from customer where cust_id='$cid'")or die(mysqli_error());
     $rowb=mysqli_fetch_array($queryb);
        $balance=$rowb['balance'];

        if ($balance>0) $disabled="disabled=true";else{$disabled="";}
?>
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Selling Rate</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
		
		$query=mysqli_query($con,"select * from temp_trans natural join product where branch_id='$branch'")or die(mysqli_error());
			$grand=0;
		while($row=mysqli_fetch_array($query)){
				$id=$row['temp_trans_id'];
				$total= $row['qty']*$row['price'];
        $total= $total - $row['discount'];
				$grand=$grand+$total;
		
?>
                      <tr >
            <td class="record"><?php echo $row['prod_name'];?></td>
						<td><?php echo $row['qty'];?></td>
						<td><?php echo number_format($row['price'],2);?></td>
            <td><?php echo number_format($row['discount'],2);?></td>
						<td><?php echo number_format($total,2);?></td>
                        <td>
							
							<a href="#updateordinance<?php echo $row['temp_trans_id'];?>" data-target="#updateordinance<?php echo $row['temp_trans_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-edit text-blue"></i></a>

              <a href="#delete<?php echo $row['temp_trans_id'];?>" data-target="#delete<?php echo $row['temp_trans_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-trash text-red"></i></a>
              
						</td>
                      </tr>
					  <div id="updateordinance<?php echo $row['temp_trans_id'];?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	  <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Sales Details</h4>
              </div>
              <div class="modal-body">
			  <form class="form-horizontal" method="post" action="transaction_update.php" enctype='multipart/form-data'>
					<input type="hidden" class="form-control" name="cid" value="<?php echo $cid;?>" required>  	
          <input type="hidden" class="form-control" name="new" value="0" required>
          <input type="hidden" class="form-control" name="did" value="<?php echo $did;?>" required>   
					<input type="hidden" class="form-control" id="price" name="id" value="<?php echo $row['temp_trans_id'];?>" required>  
				<div class="form-group">
					<label class="control-label col-lg-3" for="price">Qty</label>
					<div class="col-lg-9">
					  <input type="number" min="1" class="form-control" id="qty2" name="qty" value="<?php echo $row['qty'];?>" required>  
					</div>
          <label class="control-label col-lg-3" for="price">Selling Rate</label>
          <div class="col-lg-9">
            <input type="number" class="form-control" id="rateEdit" name="rateEdit" step=".01" min="1" value="<?php echo $row['price'];?>" required>  
          </div>
          <label class="control-label col-lg-3" for="price">Discount</label>
          <div class="col-lg-9">
            <input type="number" class="form-control" id="discEdit" name="discEdit" step=".01" min="1" value="<?php echo $row['discount'];?>" required>  
          </div>             
				</div>
				
              </div><br>
              <div class="modal-footer">
		            <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
			
        </div><!--end of modal-dialog-->
 </div>
 <!--end of modal-->  
<div id="delete<?php echo $row['temp_trans_id'];?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete Item</h4>
              </div>
              <div class="modal-body">
        <form class="form-horizontal" method="post" action="transaction_del.php" enctype='multipart/form-data'>
          <input type="hidden" class="form-control" name="cid" value="<?php echo $cid;?>" required>  
          <input type="hidden" class="form-control" name="new" value="0" required>  
          <input type="hidden" class="form-control" name="did" value="<?php echo $did;?>" required>    
          <input type="hidden" class="form-control" id="price" name="id" value="<?php echo $row['temp_trans_id'];?>" required>  
        <p>Are you sure you want to remove <?php echo $row['prod_name'];?>?</p>
        
              </div><br>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
        </form>
            </div>
      
        </div><!--end of modal-dialog-->
 </div>
 <!--end of modal-->  
<?php }?>					  
                    </tbody>
                    
                  </table>
                </div><!-- /.box-body -->

				</div>	
               
                  
                  
				</form>	
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (right) -->
            
            <div class="col-md-3">
              <div class="box box-primary">
               
                <div class="box-body">
                  <!-- Date range -->
          <form method="post" name="autoSumForm" action="sales_update.php">
				  <div class="row">
					 <div class="col-md-12">
						  
						  <div class="form-group">
							<label for="date">Total</label>
							             <input type="hidden" class="form-control text-right" id="cid" name="cid" value="<?php echo $cid;?>">
              <input type="hidden" class="form-control text-right" id="did" name="did" value="<?php echo $did;?>">
								<input type="number" step=".01" min="0" style="text-align:right" class="form-control" id="total" name="total" placeholder="Total" 
								value="<?php echo $grand;?>" onFocus="startCalc();" onBlur="stopCalc();"  tabindex="5" readonly>
							
						  </div><!-- /.form group -->

						  <div class="form-group">
							<label for="date">Amount Due</label>
							
								<input type="number" step=".01" min="0" style="text-align:right" class="form-control" id="amount_due" name="amount_due" placeholder="Amount Due" value="<?php echo number_format($grand,2);?>" readonly>
							
						  </div><!-- /.form group -->
              
						 
              <div class="form-group" id="tendered">
                <label for="date">Cash Tendered</label><br>
                <input type="number" step=".01" min="0" style="text-align:right" class="form-control" onFocus="startCalc();" onBlur="stopCalc();"  id="cash" name="tendered" placeholder="Cash Tendered" value="0">
              </div><!-- /.form group -->
              <div class="form-group" id="change">
                <label for="date">Change</label><br>
                <input type="number" step=".01" min="0" style="text-align:right" class="form-control" id="changed" name="change" placeholder="Change" readonly>
              </div><!-- /.form group -->

<div class="form-group">
			<label for="date">Change returned</label>				
			<input type="number" step=".01" min="0" style="text-align:right" class="form-control" id="returned" name="returned" placeholder="Change paid" value="0">				
		</div><!-- /.form group -->
					</div>
					
					

				</div>	
               
                  
                 
                      <button class="btn btn-lg btn-block btn-primary" id="daterange-btn" name="cash" type="submit"  tabindex="7">
                        Complete Sales
                      </button>
					  <button class="btn btn-lg btn-block" id="daterange-btn" type="reset"  tabindex="8">
                        <a href="cancel.php">Cancel Sale</a>
                      </button>
              
				</form>	
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (right) -->
			
			
          </div><!-- /.row -->
	  
            
          </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include('../dist/includes/footer.php');?>
    </div><!-- ./wrapper -->
	<script>
  
    
      $("#cash").click(function(){
          $("#tendered").show('slow');
          $("#change").show('slow');
      });

    $(function() {

      $(".btn_delete").click(function(){
      var element = $(this);
      var id = element.attr("id");
      var dataString = 'id=' + id;
      if(confirm("Sure you want to delete this item?"))
      {
	$.ajax({
	type: "GET",
	url: "temp_trans_del.php",
	data: dataString,
	success: function(){
		
	      }
	  });
	  
	  $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
	  .animate({ opacity: "hide" }, "slow");
      }
      return false;
      });

      });
    </script>
	
	<script type="text/javascript" src="autosum.js"></script>
    <!-- jQuery 2.1.4 -->
    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="../dist/js/jquery.min.js"></script>
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
          "ordering": true,x`
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
        $("#qty1").on('input', function () {
          var num = this.value.match(/^0$/);
          if (num != null) {
            // If we have no match, value will be empty.
            this.value = "";
          }
        }); 

        $("#qty2").on('input', function () {
          var num = this.value.match(/^0$/);
          if (num != null) {
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
