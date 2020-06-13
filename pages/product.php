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
    <title>Product | <?php include('../dist/includes/title.php');?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
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
      <script type='text/javascript'>swal('Success!','Successfully added new product!','success');</script>
    <?php 
  } else if($_REQUEST['success']==2){
    ?>
      <script type='text/javascript'>swal('Success!','Successfully updated product details!','success');</script>    
  <?php 
  } else if($_REQUEST['error']==1){
    ?>
      <script type='text/javascript'>swal('Product already exists!','','error');</script>    
    <?php
  }
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
              <a class="btn btn-lg btn-success" href="#add" data-target="#add" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-plus text-white"></i></a>
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Product</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <div class="row">
	     
            
            <div class="col-xs-12">
              <div class="box box-primary">
    
                <div class="box-header">
                  <h3 class="box-title">Product List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Product Name</th>
			<th>Supplier</th>
                        <th>Qty</th>
                        <th>Remaining Qty</th>
			<th>Rate</th>
<!--       <th>Selling Price</th> -->
			<th>Total</th>
			<!--th>Category</th>
			<th>Reorder</th-->
                        <th>Change</th>
			<th>Remove / Delete</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
		
		$query=mysqli_query($con,"select * from product natural join supplier where branch_id='$branch' and is_deleted=0 order by prod_name")or die(mysqli_error());
		while($row=mysqli_fetch_array($query)){
		
?>
                      <tr>
                        <td><?php echo $row['prod_name'];?></td>
			<td><?php echo $row['supplier_name'];?></td>
      <td><?php echo $row['main_qty'];?></td>
                        <td><?php echo $row['prod_qty'];?></td>
			<td><?php echo number_format($row['prod_cost_price'],2);?></td>
<!--       <td><?php echo number_format($row['prod_price'],2);?></td>
 -->			<td><?php 
				$total = $row['main_qty']*$row['prod_cost_price'];
				echo number_format($total,2);
			    ?>
			</td>
			<!--td><?php echo $row['cat_name'];?></td>
			<td><?php echo $row['reorder'];?></td-->
                        <td>
				<a href="#updateordinance<?php echo $row['prod_id'];?>" data-target="#updateordinance<?php echo $row['prod_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-edit text-blue"></i></a>
			</td>
			<td>
				<a href="prod_del.php?pid=<?php echo $row['prod_id'];?>"><i class="glyphicon glyphicon-trash text-red"></i></a>
			</td>
                      </tr>
<div id="updateordinance<?php echo $row['prod_id'];?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	  <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Product Details</h4>
              </div>
              <div class="modal-body">
			  <form class="form-horizontal" method="post" action="product_update.php" enctype='multipart/form-data'>
        <div class="form-group">
          <label class="control-label col-lg-3" for="price">Serial #</label>
          <div class="col-lg-9">
            <input type="text" class="form-control" id="price" name="serial" value="<?php echo $row['serial'];?>" required>  
          </div>
        </div>
                
				<div class="form-group">
					<label class="control-label col-lg-3" for="name">Product Name</label>
					<div class="col-lg-9"><input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['prod_id'];?>" required>  
					  <input type="text" class="form-control" id="name" name="prod_name" value="<?php echo $row['prod_name'];?>" required>  
					</div>
				</div> 
				<div class="form-group">
					<label class="control-label col-lg-3" for="file">Supplier</label>
					<div class="col-lg-9">
					    <select class="form-control select2" style="width: 100%;" name="supplier" required>
					      <?php
						
							$query2=mysqli_query($con,"select * from supplier")or die(mysqli_error());
							  while($row2=mysqli_fetch_array($query2)){
					      ?>
							    <option value="<?php echo $row2['supplier_id'];?>"><?php echo $row2['supplier_name'];?></option>
					      <?php }?>
					    </select>
					</div>
				</div> 

        <div class="form-group">
          <label class="control-label col-lg-3" for="price">Quantity</label>
          <div class="col-lg-9">
            <input type="number" step=".01" min="1" class="form-control" id="qty1" name="qty" value="<?php echo $row['main_qty'];?>" required>  
          </div>
        </div>
				
				<div class="form-group">
					<label class="control-label col-lg-3" for="price">Cost Price</label>
					<div class="col-lg-9">
					  <input type="number" step=".01" min="0" class="form-control" id="cost_price" name="prod_cost_price" value="<?php echo $row['prod_cost_price'];?>" required>  
					</div>
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
			<th>Supplier</th>
                        <th>Qty</th>
			<th>Rate</th>
			<th>Total</th>
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
<div id="add" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content" style="height:auto">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add New Product</h4>
              </div>
              <div class="modal-body">
        <form class="form-horizontal" method="post" action="product_add.php" enctype='multipart/form-data'>
        <div class="form-group">
          <label class="control-label col-lg-3" for="name">Product Name</label>
          <div class="col-lg-9"><input type="hidden" class="form-control" id="id" name="id" required>  
            <input type="text" class="form-control" id="name" name="prod_name" placeholder="Product Name" required>  
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3" for="price">Product Code</label>
          <div class="col-lg-9">
            <input type="text" class="form-control" id="serial" name="serial" placeholder="Product Code">  
          </div>
        </div> 
        <div class="form-group">
          <label class="control-label col-lg-3" for="file">Supplier</label>
          <div class="col-lg-9">
              <select class="form-control select2" style="width: 100%;" name="supplier" required>
                <?php
            
              $query2=mysqli_query($con,"select * from supplier")or die(mysqli_error());
                while($row2=mysqli_fetch_array($query2)){
                ?>
                  <option value="<?php echo $row2['supplier_id'];?>"><?php echo $row2['supplier_name'];?></option>
                <?php }?>
              </select>
          </div>
        </div>
 
        <div class="form-group">
          <label class="control-label col-lg-3" for="price">Qty</label>
          <div class="col-lg-9">
            <input type="number" step=".01" min="1" class="form-control" id="qty2" name="prod_qty" placeholder="Product Quantity" oninput="enablePrice()" required>  
          </div>
        </div>        

        <div class="form-group">
          <label class="control-label col-lg-3" for="price">Cost Price</label>
          <div class="col-lg-9">
            <input type="number" step=".01" min="0" class="form-control" id="rate" name="prod_cost_price" oninput="changeTotal()" value=0 required disabled>  
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-lg-3" for="total">Total cost</label>
          <div class="col-lg-9">
            <input type="number" step=".01" min="0" class="form-control" id="total" name="prod_total" oninput="changePrice()" value=0 required disabled>  
          </div>
        </div>
        
        
              </div>
              <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
        </form>
            </div>
      
        </div><!--end of modal-dialog-->
 </div>
 <!--end of modal--> 
    <!-- jQuery 2.1.4 -->
    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
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

        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
	function changeTotal() {
		total = document.getElementById("total");
		price = document.getElementById("rate");
		qty = document.getElementById("qty2");
		total.value = price.value*qty.value;
		if(price.value<=0){
			total.value=0;	
		}
	}
	function enablePrice() {
		total = document.getElementById("total");
		price = document.getElementById("rate");
		qty = document.getElementById("qty2");
		if(qty.value>0){
			total.value = price.value*qty.value;
			price.value = total.value/qty.value;
			total.disabled=false;
			price.disabled=false;
		} else {
			total.value=0;
			price.value=0;
			total.disabled=true;
			price.disabled=true;
		}
	}
	function changePrice() {
		total = document.getElementById("total");
		price = document.getElementById("rate");
		qty = document.getElementById("qty2");
		if(qty.value>0){
			price.value = total.value/qty.value;
		}
		if(total.value<0){
			price.value=0;	
		}
	}
    </script>
  </body>
</html>
