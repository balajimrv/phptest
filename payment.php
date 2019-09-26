<?php include('includes/header.php');
				
if($_REQUEST['checkoutShipping'] == 'yes'){	
	$_SESSION['randum'] = strtoupper($cnf->random_string('alnum', 10)); // Random String for order id
	
	$subTotal = $_REQUEST['subtot'];
	//$GrandTot = $_REQUEST['itemAmount'];
	
	$shipping = "0.00";
	$cod_charges = "0.00";
				
	$fieldarray = array(	
		"order_number"=>$_SESSION['randum'],
		"order_date"=>$cnf->datetime_format(),
		"order_status"=>'New',
		"shipping_cost"=>$shipping,
		"sub_total"=>$subTotal,		
		"grand_total"=>$subTotal,
		"bill_name"=>$_REQUEST['billFirstName'],
		"bill_phone"=>$_REQUEST['billPhone'],
		"bill_email"=>$_REQUEST['billEmail'],
		"bill_address"=>$_REQUEST['billAddress'],
		"bill_city"=>$_REQUEST['billCity'],
		"bill_zip"=>$_REQUEST['billZip']
	);
	$order_new_id = $cnf->insert(TBL_ORDER,$fieldarray);
				
		
		//Get cart details from cart table
		$strSqlCart = "select * from ".TBL_CART." where cart_session_id='".$_SESSION['session_id']."'";
		$resCart = mysql_query($strSqlCart);
		$resCartno = mysql_num_rows($resCart);
		while($rowsCart = mysql_fetch_object($resCart)){
		
				$fieldarray = array(	
				"order_id"=>$order_new_id,
				"product_id"=>$rowsCart->product_id,
				"quantity"=>$rowsCart->cart_qty,
				"product_price"=>$rowsCart->product_price		
			);
			$cnf->insert(TBL_ORDER_ITEM,$fieldarray);			
		}
	}
											
	if($_POST['orderId'] !=""){
		$orderId = $_POST['orderId'];
	}else{
		$orderId = $order_new_id;
}
				
?>				
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
   
    <li class="breadcrumb-item">Shopping Cart</li>
    <li class="breadcrumb-item">Checkout/Shipping</li>
    <li class="breadcrumb-item active" aria-current="page">Payment</li>
  </ol>
</nav>
		<div id="main-container" class="container">
		<!-- Breadcrumb Starts -->
			
             
		<!-- Breadcrumb Ends -->
		<!-- Main Heading Starts -->
			<h2 class="main-heading text-center">
				Payment
			</h2>
		<!-- Main Heading Ends -->
		<!-- Shopping Cart Table Starts -->
			<div class="table-responsive shopping-cart-table">
            
            
            <div class="panel panel-smart">
							
							<div class="panel-body">
                            
                            
            	<?php 
				$strSqlCart1 = "SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
				$resCart1 = mysql_query($strSqlCart1);
				$norows = @mysql_num_rows( $resCart1 );
				$intHandeling1 = 0;
				$intHandeling = 0;
				$subTotal = 0;
				if($norows!=""){?>
				<table class="table table-hover table-bordered">
					<tr class="info">
						<th width="5%"><strong>S.No</strong></th>
						<th width="40%"><strong>Item</strong></th>
                        <th width="12%"><strong>Qty.</strong></th>
						<th width="15%"><strong>Unit Price</strong></th>
						<th width="10%"><strong>Discount</strong></th>						
						<th width="15%"><strong>Total</strong></th>
					</tr>
					<?php
						//Get cart details from cart table
						//$resCart = mysql_query("SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'");
						//$resCartno = mysql_num_rows( $resCart );
						$i=1;
						while($rowsCart = mysql_fetch_object($resCart1))
						{
						$strSqlProduct = "SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$rowsCart->product_id."";
						$resProduct = mysql_query($strSqlProduct);
						$resProductno = mysql_num_rows( $resProduct );
						$rowsProduct = mysql_fetch_object($resProduct);
						
						?>
						<tr>
							<td nowrap><?php echo $i; ?></td>
							<td nowrap>
								<?php 
								  $strLen = strlen($rowsProduct->product_name);
								  if($strLen > 42){
									echo substr($rowsProduct->product_name, 0, 42);
									echo '...';
								  }else{
									echo $rowsProduct->product_name;
								  }
								?>							</td>
                            <td nowrap><?php echo $rowsCart->cart_qty; ?></td>
							<td nowrap><?php echo '$. '.$rowsProduct->product_price; ?></td>
							<td nowrap><?php if($rowsProduct->product_discount == 0){ echo '----'; } else {echo $rowsProduct->product_discount.' %'; } ?></td>
							 	
							
							<td nowrap>
								<div align="right">
								  <?php 
								  	$product_price = $cnf->calculate_discount($rowsProduct->product_price, $rowsProduct->product_discount);
									
									$Price = ($product_price * $rowsCart->cart_qty); 
									echo '$. '. $cnf->displayAmount($Price); 
								?>
						        </div></td>
						</tr>
					<?php 
						$subTotal = $subTotal+$Price; //Sub total						
						$grandTotal = $subTotal+$shipping; //Estimated total
						$i++;
					}
					?>
				</table>
                
                <table align="right" style="margin-right: 5px;">
						<tr>
							<td align="right">Subtotal : </td>
							<td width="5">&nbsp;</td>
							<td><div align="right"><?php echo "$. ".$cnf->displayAmount($subTotal); ?></div></td>
						</tr>
						<tr>
							<td align="right">Shipping : </td>
							<td>&nbsp;</td>
							<td><div align="right">
							<?php echo "$. ".$cnf->displayAmount($shipping); ?>
							</div></td>
						</tr>
						<tr style="font-weight:bold;">
							<td align="right"><strong>Estimated Total :</strong> </td>
							<td>&nbsp;</td>
							<td><div align="right"><strong><?php echo "$. ".$cnf->displayAmount($grandTotal); ?></strong></div></td>
						</tr>
					</table>
            	
                
                		<?php
						$amount = $grandTotal;
						?>
                        
                        <div class="row">
                        
                        <div class="col-sm-12">
                        
                        <?php
                    function getHash($key,$SALT,$txnid,$amount,$firstname,$email,$phone,$productinfo,$successUrl,$failureUrl){
                    $hash = '';
                    // Hash Sequence
                    $hashSequence = "key|txnid|amount|productinfo|name|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
					                    
                     $posted = array();
                     $posted['key'] = $key;
                     $posted['txnid'] = $txnid;
                     $posted['amount'] = $amount;
                     $posted['name'] = $firstname;
                     $posted['email'] = $email;
                     $posted['phone'] = $phone;
                     $posted['productinfo'] = $productinfo;
                     $posted['success_url'] = $successUrl;
                     $posted['failure_url'] = $failureUrl;
                             
                    $hashVarsSeq = explode('|', $hashSequence);
                    $hash_string = '';
                    foreach($hashVarsSeq as $hash_var) {
                      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                      $hash_string .= '|';
                    }
                    $hash_string .= $SALT;
                    $hash = strtolower(hash('sha512', $hash_string));
                    return $hash;
                    }
                    // Merchant key
                    $MERCHANT_KEY = "zz1XzIJB";
                    
                    // Merchant Salt
                    $SALT = "zgdW3PDB1o";
                                        
                    
                    $Amount = $grandTotal;
					
					if(empty($posted['txnid'])) {
					  // Generate random transaction id
					  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
					} else {
					  $txnid = $posted['txnid'];
					}

                    //$txnid = $_SESSION['randum']; //unique Id that should be passed to payment gateway
                    
                    $successUrl = "success.php";                    
                    $failureUrl = "failure.php";
                    $proInfo ="Purchase of PHP Test Online Store";
                    //$txnid = substr(hash('sha256', mt_rand(). microtime()), 0, 20);
                    $hashValue = getHash($MERCHANT_KEY,$SALT,$txnid,$Amount,$_REQUEST['billFirstName'],$_REQUEST['billEmail'],$_REQUEST['billPhone'],$proInfo,$successUrl,$failureUrl);
                    ?>
							
							<div class="pull-right" style="margin-top:40px;">
               			 <form method="post" name="customerData" action="success.php">
							<input type="hidden" name="merchant_id" value="59471">
                            <input type="hidden" name="order_id" value="<?php echo $_SESSION['randum']; ?>">
							<input type="hidden" name="amount" value="<?php echo $amount; ?>">
                            <input type="hidden" name="currency" value="USD">                           
                            
      						<input type="hidden" name="hash" value="<?php echo $hashValue; ?>"/>
      						<input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                            
							<input type="hidden" name="redirect_url" value="return.php">
                            <input type="hidden" name="success_url" value="success.php">
                            <input type="hidden" name="language" value="EN">
							
							<input type="hidden" name="billing_name" value="<?php echo $_REQUEST['billFirstName']; ?>">
							<input type="hidden" name="billing_address" value="<?php echo $_REQUEST['billAddress']; ?>">
							<input type="hidden" name="billing_city" value="<?php echo $_REQUEST['billCity']; ?>">
							<input type="hidden" name="billing_zip" value="<?php echo $_REQUEST['billZip']; ?>">
                            <input type="hidden" name="billing_country" value="<?php echo $_REQUEST['selCountry']; ?>">
							<input type="hidden" name="billing_tel" value="<?php echo $_REQUEST['billPhone']; ?>">
							<input type="hidden" name="billing_email" value="<?php echo $_REQUEST['billEmail']; ?>">
							
							<input type="hidden" name="delivery_name" value="<?php echo $_REQUEST['billFirstName']; ?>">
							<input type="hidden" name="delivery_address" value="<?php echo $_REQUEST['billAddress']; ?>">
                            <input type="hidden" name="delivery_city" value="<?php echo $_REQUEST['billCity']; ?>">
                            <input type="hidden" name="delivery_zip" value="<?php echo $_REQUEST['billZip']; ?>">
                            <input type="hidden" name="delivery_country" value="<?php echo $_REQUEST['selCountry']; ?>">
							<input type="hidden" name="delivery_tel" value="<?php echo $_REQUEST['billPhone']; ?>">		
                            
                            <input type="hidden" name="merchant_param1" value="additional Info."/>
                            <input type="hidden" name="merchant_param2" value="additional Info."/>
                            <input type="hidden" name="merchant_param3" value="additional Info."/>
                            <input type="hidden" name="merchant_param4" value="additional Info."/>
                            <input type="hidden" name="merchant_param5" value="additional Info."/>
                            <input type="hidden" name="promo_code" value=""/>
                            <input type="hidden" name="customer_identifier" value="<?php echo $proInfo; ?>"/>
							
							<input type="image" name="submit" border="0" src="images/Pay-Now-Button.png" alt="PAY Now" />
						</form>
                        </div></div>
                
                		</div>
                <?php
				 					
						//Remove cart list.	
						$strSqlCartDel = "DELETE FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
						$resCartDel = mysql_query($strSqlCartDel);
						
						//Clear session id for user
						if($_SESSION['session_id'] !=''){
							unset($_SESSION['session_id']);
						}
					}else{
						header("Location:cart.php");
					}
					?>
                    
                    
                    </div>
			</div>
	
				</div>
			
   
		
		</div>
	<!-- Main Container Ends -->
	<?php include('includes/footer.php'); ?>