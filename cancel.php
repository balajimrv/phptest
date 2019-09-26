<?php include('includes/header.php'); 
if(empty($_SESSION['order_number'])){ header("Location: index.php"); }
$strSql = "SELECT * FROM ".TBL_ORDER." WHERE order_number = '".$_SESSION['order_number']."' LIMIT 1";
if($cnf->records_fetch($strSql)==true) {
//Status Update 
$fieldarray = array(	
	"order_status"=>'Cancel' // Cancel
  );

$cnf->update(TBL_ORDER,$fieldarray,'order_id',$_SESSION['order_id']);
}

?>

      <div class="page-header">
        <h1 style="color:#F30 !important; padding-top:20px;">Order Status</h1>
      </div>
            
      <div class="row">
      
      <div class="alert alert-danger" role="alert">Your Order has been canceled!</div>
      </div>
      
      <div class="row">
      <div class="alert alert-success" role="alert">
        
        <?php
        $strSql = "SELECT * FROM ".TBL_ORDER." WHERE order_id = '".$_SESSION['order_id']."' LIMIT 1";
        $strQry = mysql_query($strSql);
		$rowQry = mysql_fetch_object($strQry);
        ?>
          <table class="table table-bordered">           
              <tr>                
                <td>Order Number</td>
                <td><?php echo $rowQry->order_number; ?></td>
              </tr>
              <tr>                
                <td>Order Date</td>
                <td><?php echo $cnf->convetdate($rowQry->order_date) ?></td>
              </tr>
              <tr>                
                <td>Merchant Name</td>
                <td><?php echo $_SESSION['customer_identifier']; ?></td>
              </tr>
              <tr>                
                <td>Order Amount</td>
                <td>USD <?php echo $rowQry->grand_total; ?></td>
              </tr>
          </table>
        
      </div>
      
        
      </div>
        
       <div class="row">
       	<div class="col-md-12"><a href="return.php" class="btn btn-lg btn-primary">Return To Shopping Cart Page</a></div>
      </div>
      
    </div> <!-- /container -->
    
<?php
include('includes/footer.php');

?>
	
