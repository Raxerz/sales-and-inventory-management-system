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
    <title>Product | <?php include('../dist/includes/title.php');?></title>
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
      <script type='text/javascript'>swal('Success!','Successfully added new stocks!','success');</script>
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
              <a class="btn btn-lg btn-warning" href="home.php">Back</a>
              
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Product</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <div class="row">
	      <div class="col-md-4">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Stockin Products</h3>
                </div>
                <div class="box-body">
                  <!-- Date range -->
                  <form method="post" action="stockin_add.php" enctype="multipart/form-data">
  
                  <div class="form-group">
                    <label for="date">Product Name</label>
                    <div class="input-group col-md-12">
                      <select class="form-control select2" name="prod_name" required>
                      <?php
			 include('../dist/includes/dbcon.php');
				$query2=mysqli_query($con,"select * from product where branch_id='$branch' and is_deleted=0 order by prod_name")or die(mysqli_error());
				  while($row=mysqli_fetch_array($query2)){
		      ?>
				    <option value="<?php echo $row['prod_id'];?>"><?php echo $row['prod_name'];?></option>
		      <?php }?>
                    </select>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
		  
                  <div class="form-group">
                    <label for="date">Quantity</label>
                    <div class="input-group col-md-12">
                      <input type="number" step=".01" min="1" class="form-control pull-right" id="qty1" name="qty" placeholder="Quantity" value="1" required>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                  <div class="form-group">
                    <label for="date">Date</label>
                    <div class="input-group col-md-12">
                      <input type="date" class="form-control" id="date" name="date" value="<?php print(date("Y-m-d")); ?>" required> 
                    </div><!-- /.input group -->
                  </div><!-- /.form group --> 
<!--                   <div class="form-group">
                    <div class="input-group">
                      <button class="btn btn-primary" id="daterange-btn" name="">
                        Save
                      </button>
					  <button class="btn" id="daterange-btn">
                        Clear
                      </button>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="input-group col-md-12">
                      <button class="btn btn-success" name="" style="width:100%;margin-bottom:5%">
                        Save
                      </button>
                       <button class="btn btn-danger" id="daterange-btn" style="width:100%;" type="reset">
                        Clear
                      </button>
                    </div>
                  </div><!-- /.form group -->                  
				</form>	
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (right) -->
            
            <div class="col-xs-8">
              <div class="box box-primary">
    
                <div class="box-header">
                  <h3 class="box-title">Product Stockin List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Qty</th>
			<th>Total</th>
		        <th>Supplier</th>
		        <th>Date Delivered</th>
                        <th>Change</th>
			<th>Remove / Delete</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
		$branch=$_SESSION['branch'];
		$query=mysqli_query($con,"select * from stockin natural join product natural join supplier where branch_id='$branch' and is_deleted=0  order by date desc")or die(mysqli_error());
		while($row=mysqli_fetch_array($query)){
		
?>
                      <tr>
                        <td><?php echo $row['prod_name'];?></td>
                        <td><?php echo $row['qty'];?></td>
			<td><?php 
				$total = $row['qty']*$row['prod_cost_price'];
				echo number_format($total,2);
			    ?>
			</td>
			<td><?php echo $row['supplier_name'];?></td>
			<td><?php echo date("d M Y",strtotime($row['date']));?></td>
                        <td>
				<a href="#updateordinance<?php echo $row['stockin_id'];?>" data-target="#updateordinance<?php echo $row['stockin_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-edit text-blue"></i></a>
			</td>
			<td>
				<a href="stockin_del.php?id=<?php echo $row['stockin_id'];?>&qty=<?php echo $row['qty'];?>&pid=<?php echo $row['prod_id'];?>"><i class="glyphicon glyphicon-trash text-red"></i></a>
			</td>
                      
                      </tr>
<div id="updateordinance<?php echo $row['stockin_id'];?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	  <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Stock Start Details</h4>
              </div>
              <div class="modal-body">
		  <form class="form-horizontal" method="post" action="stockin_update.php" enctype='multipart/form-data'>
			<input type="hidden" class="form-control" id="prod_id" name="prod_id" value="<?php echo $row['prod_id'];?>" required> 
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['stockin_id'];?>" required>  
			<div class="form-group">
				<label class="control-label col-lg-3" for="file">Quantity</label>
				<div class="col-lg-9">
          <input type="number" step=".01" min="1" class="form-control pull-right" id="qty1" name="qty" placeholder="Quantity" value="<?php echo $row['qty'];?>" required>
				</div>
			</div>
      <br><br>
         <div class="form-group">
        <label class="control-label col-lg-3">Date</label>
        <div class="col-lg-9">
          <input type="date" class="form-control" id="date" name="date_update" value="<?php echo $row['date'];?>" required> 
        </div><!-- /.input group -->
      </div>
              </div><br><br><br><br><br><br><br>
              <div class="modal-footer">
		<button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
			
        </div><!--end of modal-dialog-->
 </div>
 <!--end of modal-->                      
<?php }?>			  
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Product Name</th>
                        <th>Qty</th>
			<th>Total</th>
                        <th>Supplier</th>
                        <th>Date Delivered</th>
                        <th>Change</th>
			<th>Remove / Delete</th>                        
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
          var num1 = this.value.match(/^0$/);
          if (num === null || num1 != null) {
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
      });
    </script>
  </body>
</html>
