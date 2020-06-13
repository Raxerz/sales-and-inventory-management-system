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
      <script type='text/javascript'>swal('Success!','Successfully disposed stocks!!','success');</script>
    <?php } else if($_REQUEST['error']==1){
      ?>
      <script type='text/javascript'>swal('Error!','Stock sold is greater than stock available','error');</script>      
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
<!-- 	      <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Stockout Products</h3>
                </div>
                <div class="box-body">
                  <form method="post" action="stockout_add.php" enctype="multipart/form-data">
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
                    </div>
                  </div>
		  
                  <div class="form-group">
                    <label for="date">Quantity</label>
                    <div class="input-group col-md-12">
                      <input type="number" step=".01" min="1" class="form-control" id="qty" name="prod_qty" placeholder="Quantity" oninput="enablePrice()" required> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="date">Price</label>
                    <div class="input-group col-md-12">
                      <input type="number" step=".01" min="0" class="form-control" id="rate" name="prod_price" oninput="changeTotal()" value=0 required disabled>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="date">Total</label>
                    <div class="input-group col-md-12">
                      <input type="number" step=".01" min="0" class="form-control" id="total" name="prod_total" oninput="changePrice()" value=0 required disabled> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="date">Date</label>
                    <div class="input-group col-md-12">
                      <input type="date" class="form-control" id="date" name="date" value="<?php print(date("Y-m-d")); ?>" required> 
                    </div>
                  </div>                 
                <div id="dynamicInput">
		
				</div> 
                  
                  <div class="form-group">
                    <div class="input-group col-md-12">
                      <button class="btn btn-success" id="daterange-btn" name="" style="width:100%;margin-top:10%;margin-bottom:10%">
                        Add
                      </button>
					             <button class="btn btn-danger" id="daterange-btn" style="width:100%;" type="reset">
                        Clear
                      </button>
                    </div>
                  </div>
				</form>	
                </div>
              </div>
            </div> -->
            
            <div class="col-xs-12">
              <div class="box box-primary">
    
                <div class="box-header">
                  <h3 class="box-title">Product Stockout List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Qty</th>
<!-- 			<th>Purchase Total</th>
			<th>Sold Total</th>
			<th>Profit</th>
			<th>Loss</th> -->
			<th>Remaining Qty</th>
                        <!-- <th>Change</th> -->
			<th>Remove / Delete</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
		$branch=$_SESSION['branch'];
		$query=mysqli_query($con,"select * from stockout natural join product where branch_id='$branch' order by date desc")or die(mysqli_error());
		$all_purchase_total=0;
		$all_sold_total=0;
		$profit=0;
		$loss=0;
		while($row=mysqli_fetch_array($query)){	
      if($row['qty']>0){
			$cost_total=0;
			$sold_total=0;
?>
                      <tr>
                        <td><?php echo date("d M Y",strtotime($row['date']));?></td>
                        <td><?php echo $row['prod_name'];?></td>
                        <td><?php echo $row['qty'];?></td>
<!-- 			<td><?php 
				$cost_total = $row['qty']*$row['prod_cost_price'];
				$all_purchase_total+=$cost_total;
				echo number_format($cost_total,2);
			    ?>
			</td>
			<td><?php 
				$sold_total = $row['qty']*$row['sell_price'];
				$all_sold_total += $sold_total;
				echo number_format($sold_total,2);
			    ?>
			</td>
			<td><?php
				if($sold_total<$cost_total){
					echo "0";
				} else{
					echo number_format($sold_total-$cost_total,2);
					$profit+=($sold_total-$cost_total);
				}
			    ?>			  
			</td>
			<td><?php
				if($sold_total>$cost_total){
					echo "0";
				} else{
					echo number_format($cost_total-$sold_total,2);
					$loss+=($cost_total-$sold_total);
				}
			    ?>
			</td> -->
			<td><?php echo $row['prod_qty'];?></td>
                        <!-- <td>
				<a href="#updateordinance<?php echo $row['stockout_id'];?>" data-target="#updateordinance<?php echo $row['stockout_id'];?>" data-toggle="modal" style="color:#fff;" class="small-box-footer"><i class="glyphicon glyphicon-edit text-blue"></i></a>
			</td> -->
                        <td>
							<a href="stockout_del.php?id=<?php echo $row['stockout_id'];?>&qty=<?php echo $row['qty'];?>&pid=<?php echo $row['prod_id'];?>&date=<?php echo $row['date'];?>"><i class="glyphicon glyphicon-trash text-red"></i></a>
						</td>
                      </tr>
                
<?php }}?>					  
                    </tbody>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
<!-- 			<th id="all_purchase_total"><?php echo number_format($all_purchase_total,2)?></th>
			<th id="all_sold_total"><?php echo number_format($all_sold_total,2)?></th>
			<th id="all_profit"><?php echo number_format($profit,2)?></th>
			<th id="all_loss"><?php echo number_format($loss,2)?></th> -->
			
			<th></th>
                        <th></th>
			<th></th>
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
        $("input[type='number']").on('input', function () {
          var num = this.value.match(/^\d+$/);
          if (num === null) {
            // If we have no match, value will be empty.
            this.value = "";
          }
        });  
        $("#qty").on('input', function () {
          var num = this.value.match(/^0$/);
          if (num != null) {
            // If we have no match, value will be empty.
            this.value = "";
          }
        });           
        var table = $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
	table.on('search.dt', function(){
		var purchaseTotal = (table.column( 2,  { search:'applied' } ).data().reduce(function(a, b) { 
			return parseFloat(a.toString().replace(",","")) + parseFloat(b.toString().replace(",","")); 
		}, 0));
		var soldTotal = (table.column( 3,  { search:'applied' } ).data().reduce(function(a, b) { 
			return parseFloat(a.toString().replace(",","")) + parseFloat(b.toString().replace(",","")); 
		}, 0));
		var profit = (table.column( 4,  { search:'applied' } ).data().reduce(function(a, b) { 
			return parseFloat(a.toString().replace(",","")) + parseFloat(b.toString().replace(",","")); 
		}, 0));
		var loss = (table.column( 5,  { search:'applied' } ).data().reduce(function(a, b) { 
			return parseFloat(a.toString().replace(",","")) + parseFloat(b.toString().replace(",","")); 
		}, 0));
		document.getElementById("all_purchase_total").innerHTML=purchaseTotal.toLocaleString( undefined,{minimumFractionDigits:2});
		document.getElementById("all_sold_total").innerHTML=soldTotal.toLocaleString( undefined,{minimumFractionDigits:2});
		document.getElementById("all_profit").innerHTML=profit.toLocaleString( undefined,{minimumFractionDigits:2});
		document.getElementById("all_loss").innerHTML=loss.toLocaleString( undefined,{minimumFractionDigits:2});
	});
      });
    </script>
     <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();              
        //Date range picker
        $('#date').daterangepicker();
        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        
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
	function changeTotal() {
		total = document.getElementById("total");
		price = document.getElementById("rate");
		qty = document.getElementById("qty");
		total.value = price.value*qty.value;
		if(price.value<=0){
			total.value=0;	
		}
	}
	function enablePrice() {
		total = document.getElementById("total");
		price = document.getElementById("rate");
		qty = document.getElementById("qty");
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
		qty = document.getElementById("qty");
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
