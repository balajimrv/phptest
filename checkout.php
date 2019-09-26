<?php include('includes/header.php');

if($_GET["act"]=="session_expired") {
	$strMsgLogin = Session_Expired;
	echo $cnf->HideMsg();
}


?>
<script type="text/javascript">
	
	function validShipBilling(frm){
		var result = true;
		if(result) result = validateRequired(frm.billFirstName, 'Please enter your full name');		
		if(result) result = validateRequired(frm.billPhone, 'Please enter your phone number');
		if(result) result = validateRequired(frm.billEmail, 'Please enter your email');
		if(result) result = validateEmail(frm.billEmail, 'Invalid email id');
		if(result) result = validateRequired(frm.billAddress, 'Please enter your shipping address');
		if(result) result = validateRequired(frm.billCity, 'Please enter your city');
		if(result) result = validateRequired(frm.billZip, 'Please enter your zip code');
		return result;
	}
</script>

  <!-- Breadcrumbs -->  
  
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
    <li class="breadcrumb-item"><a href="cart.php">Shopping Cart</a></li>
    <li class="breadcrumb-item active" aria-current="page">Checkout/Shipping</li>
  </ol>
</nav>
  <!-- Breadcrumbs End --> 
  
  <!-- Main Container -->
  <section class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">
        
            <div class="page-title mb20">
              <h2 class="text-center ">Checkout / Shipping Information </h2>
            </div>
            
			<?php 
			$strSqlCart1 = "SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
			$resCart1 = mysql_query($strSqlCart1);
			$no_rows = @mysql_num_rows( $resCart1 );
			if($no_rows <= 0 ){ echo("<script>location.href = 'cart.php';</script>"); }
			$intHandeling1 = 0;
			$intHandeling = 0;
			$SubTotal = 0;
			if($no_rows!=""){
						
						while($rowsCart = mysql_fetch_object($resCart1))
						{
						$strSqlProduct = "SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$rowsCart->product_id."";
						$resProduct = mysql_query($strSqlProduct);
						$resProductno = mysql_num_rows( $resProduct );
						$rowsProduct = mysql_fetch_object($resProduct);
						
						$product_price = $cnf->calculate_discount($rowsProduct->product_price, $rowsProduct->product_discount);
						
						$Price = ($product_price * $rowsCart->cart_qty);
						$SubTotal = $SubTotal+$Price; //Sub total
						$grandTotal = $SubTotal; //Estimated total
						
						$itemsCount = $itemsCount + $rowsCart->cart_qty;
					}
					?>
                    
                    <div class="row">
                        <div class="col-sm-8 col-md-offset-2">
                        
                        	<div class="panel panel-smart">
							
							<div class="panel-body">
							<!-- Form Starts -->
								
                            <div class="col-sm-offset-1 col-sm-10">
                            
                                <form role="form" class="form-horizontal" name="frmBill" id="frmBill" method="post" action="payment.php" onsubmit="return validShipBilling(this)">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">Full Name :</label>
                        <div class="col-sm-9">
                        <input name="billFirstName" class="form-control" type="text" id="billFirstName" value="">
                        </div>
                    </div>
                                           
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">Phone : </label>
                        <div class="col-sm-9">
                        
                        <input name="billPhone" class="form-control" type="text" id="billPhone" value="" onkeypress="return numbersonly(this, event)" maxlength="13" />
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">Email Id :</label>
                        <div class="col-sm-9">
                        
                        <input name="billEmail" class="form-control" type="text" id="billEmail" value=""  />
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">Address :</label>
                        <div class="col-sm-9">
                        
                        <textarea class="form-control" name="billAddress" id="billAddress"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">City :</label>
                        <div class="col-sm-9">
                        
                        <input name="billCity" class="form-control" type="text" id="billCity" value="">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCountry">Zip :</label>
                        <div class="col-sm-9">
                        
                        <input name="billZip" class="form-control" id="billZip" type="text" value="" onkeypress="return numbersonly(this, event)" maxlength="6">
                        </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">                 
                    
                    
                    <input name="selCountry" type="hidden" id="selCountry" value="India">
                    <input type="hidden" name="checkoutShipping" value="yes" />
                    <input type="hidden" name="subtot" value="<?php echo $SubTotal;?>" />
                    <button type="submit" name="submit" id="submit" class="btn cbtn btn-danger">CONTINUE <i class="icon-chevron-right icon-white"></i></button>
                    </div>
                    </div>
                </form>
                            
                            </div>
							<!-- Form Ends -->
							</div>
						</div>                   
                        </div>
                    </div>
                    
               <?php } ?>  
                    
				
      </div>
    </div>
  </section>
  
<?php
include('includes/footer.php');
?>