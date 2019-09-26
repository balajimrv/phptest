<?php include('includes/header.php');
if (isset($_POST['savecart'])) {
		$_SESSION['randum'] = $cnf->random_string('alnum', 10);
		$SubTotal = $_REQUEST['subtot'];
		$GrandTot = $_REQUEST['grandtot'];
		
		$strSqlInsert = "INSERT INTO ".TBL_ORDER." (order_number, order_date, order_status, sub_total, grand_total)";
		$strSqlInsert .= " VALUES ('".$_SESSION['randum']."', '".$cnf->datetime_format()."','New',";
		$strSqlInsert .= "'".$SubTotal."', '".$GrandTot."')";
		
		$resCartInsert = mysql_query($strSqlInsert);
		
		$Orderid = mysql_insert_id();
		
		//Get cart details from cart table
		$strSqlCart = "SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
		$resCart = mysql_query($strSqlCart);
		$resCartno = mysql_num_rows($resCart);
		
		while($rowsCart = mysql_fetch_object($resCart))	{
			$strSqlProduct = "SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$rowsCart->product_id;
			$resProduct = mysql_query($strSqlProduct);
			$resProductno = mysql_num_rows($resProduct);
			$rowsProduct = mysql_fetch_object($resProduct);
			
			//Add Cart details to order item table
			$strSqlOInsert = "INSERT INTO ".TBL_ORDER_ITEM." (order_id,product_id,quantity) VALUES ('".$Orderid."','".$rowsCart->product_id."','".$rowsCart->cart_qty."')";
			$resCartOInsert = mysql_query($strSqlOInsert);
		}
		
		//Remove cart list.	
		$strSqlCartDel = "DELETE FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
		$resCartDel = mysql_query($strSqlCartDel);
		
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='checkout.php?act=myaccount'";
		echo "</script>";
	}

	if($_REQUEST['action']=='delete'){
		//delete the particular cart product
		$strSqlCartDel = "delete from ".TBL_CART." where cart_id='".$cnf->decodeval($_REQUEST['cart_id'])."'";
		$resCartDel = mysql_query($strSqlCartDel);
		$strMsg = "Successfully removed your cart item.";
		echo $cnf->HideMsg();
		//header("Location:cart.php");
	}
	
	if($_REQUEST['btnUpdate']=="Update"){	
		$count = count($_REQUEST['hidcart_id']);
		$strMsg2 = "";
		for($i=0;$i<$count;$i++){
			$strCart = mysql_query("SELECT product_id FROM ".TBL_CART." WHERE cart_id='".$_REQUEST['hidcart_id'][$i]."'");
			while($rowCart = mysql_fetch_object($strCart)){
				$strPro = mysql_query("SELECT product_name,product_stock FROM ".TBL_PRODUCTS." WHERE product_id=".$rowCart->product_id."");
				$rowPro = mysql_fetch_object($strPro);
								
				if($_REQUEST['qty'][$i] <= $rowPro->product_stock){
					$strSqlCartUpdate = "update ".TBL_CART." set cart_qty=".$_REQUEST['qty'][$i]." where cart_id='".$_REQUEST['hidcart_id'][$i]."'";
					$resCartUpdate = mysql_query($strSqlCartUpdate);
					$strMsg = "Quantity has been updated successfully!";
					echo $cnf->HideMsg();
		}else{
					$strMsg2 = $strMsg2 .= "<span style='color:#000000;'>".$rowPro->product_name. ",</span> available stock (".$rowPro->product_stock.") only <br>";
				}
			}			
		}
	}

?>

   <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
  </ol>
</nav>
  
  <!-- Main Container -->
  <section class="main-container col1-layout">
    
    <div class="main container">
      <div class="col-main">
        <div class="cart">
          <div class="page-content page-order"><div class="page-title">
            <h2 class="text-center">Your Cart Summary </h2>
          </div>
          <form method="post" name="frmCart" id="frmCart" action="">
          
                    <div class="order-detail-content">
              <div class="table-responsive">
              <?php 
			$strSqlCart1 = "SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
			$resCart1 = mysql_query($strSqlCart1);
			$norows = mysql_num_rows( $resCart1 );
			$intHandeling1 = 0;
			$intHandeling = 0;
			$SubTotal = 0;
			if($norows!=""){?>
				
               <div id="divMsg" style="padding:3px; color:#FF0000; text-align:center;"></div>
                <table class="table table-bordered cart_summary">
                  <thead>
                  
                    <tr>
                      <th colspan="2" class="cart_product">Item </th>
                      <th width="125">Quantity </th>
                      <th width="125">Price</th>                      
                      <th width="125">Discount</th>
                      <th width="125">Total</th>
                      <th width="128" class="action">Action</th>
                    </tr>
                  </thead>
                  <tbody>
				   <?php
                    //Get cart details from cart table
                    //$resCart = mysql_query("SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'");
                    //$resCartno = mysql_num_rows( $resCart );
                    
                    while($rowsCart = mysql_fetch_object($resCart1))
                    {
                    $strSqlProduct = "SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$rowsCart->product_id."";
                    $resProduct = mysql_query($strSqlProduct);
                    $resProductno = mysql_num_rows( $resProduct );
                    $rowsProduct = mysql_fetch_object($resProduct);
                    
                    ?>
                    <tr>
                      <td class="cart_product" colspan="2">
                      
                      <img src="products/<?php echo $rowsProduct->product_image; ?>" style="width:60px; height:70px;" class="img-thumbnail" border="0">								
                     
                      <a href="product-details.php?pid=NDA=" title="shirt">
					  <?php 
								  $strLen = strlen($rowsProduct->product_name);
								  if($strLen > 42){									
									echo substr($rowsProduct->product_name, 0, 42);
									echo '...';									
								  }else{									
									echo $rowsProduct->product_name;
								  }
								?>
                      </a>
							
                      </td>
                     <!-- <td class="cart_description"><p class="product-name"><a href="#">Ipsums Dolors Untra </a></p>
                        <small><a href="#">Color : Red</a></small>
                        <small><a href="#">Size : M</a></small></td>-->
                      <td class="qty">
                      <input type="text" name="qty[]" id="qty" value="<?php echo $rowsCart->cart_qty; ?>" size="3" maxlength="3" onKeyUp="checkNumber(this);">
                      <input name="hidcart_id[]" type="hidden" value="<?php echo $rowsCart->cart_id; ?>">
                      <input name="hidPId[]" type="hidden" value="<?php echo $rowsCart->product_id; ?>"><strong></strong>                      
                      </td>
                      
                      <td class="price"><span><?php echo '$. '.$rowsProduct->product_price; ?></span></td>
                      
                      <td class="availability"><span><?php if($rowsProduct->product_discount == 0){ echo '----'; } else {echo $rowsProduct->product_discount.' %'; } ?></span></td>
                      
                      <td class="price1 ">
                      <span>
					  <?php 
							$product_price = $cnf->calculate_discount($rowsProduct->product_price, $rowsProduct->product_discount);
							
							$Price = ($product_price * $rowsCart->cart_qty); 
							echo '$. '. $cnf->displayAmount($Price); 
					  ?>
                      </span>
                      </td>
                      <td class="action">
                      <button type="submit" title="Update" name="btnUpdate" value="Update" id="btnUpdate"class="btn btn-default tool-tip" >
						<span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
					  </button>&nbsp;&nbsp;&nbsp;
                      <a href="cart.php?action=delete&cart_id=<?php echo $cnf->encodeval($rowsCart->cart_id); ?>" onClick="javascript:if(confirm('Are you sure want to remove?')) { return true; } else{ return false; };" title="Remove" class="btn btn-default tool-tip">
                      <span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span>
                      </a>
                      </td>
                    </tr>
                      <?php 
						$SubTotal = $SubTotal+$Price; //Sub total
						$grandTotal = $SubTotal; //Estimated total
					}
					?>		
                       	
                
                  
                </tbody>
                <tfoot>
                    <tr >
                      <td style="border:none !important; border-right:1px solid #ddd !important;" colspan="5" class="text-right">Sub Total</td>
                      <td style="border:none !important; text-align:right;" colspan="1"><strong><?php echo "$. ".$cnf->displayAmount($SubTotal); ?></strong></td>
                      <td  style="border:none !important;"></td>
                    </tr>
                    <tr > 
                      <td  style="border:none !important; border-right:1px solid #ddd !important;" colspan="5" class="text-right"><strong>Grand Total</strong></td>
                      <td style="border:none !important; text-align:right;" colspan="1" ><strong class="text-danger"><?php echo "$. ".$cnf->displayAmount($SubTotal); ?></strong></td>
                      <td  style="border:none !important;"></td>
                    </tr>
                  </tfoot>
                
                </table>
              </div>
              	
              <div class="cart_navigation"> 
              	<a class="btn btn-success pull-left" href="index.php"><i class="fa fa-arrow-left"> </i>&nbsp; Continue shopping</a> 
              	<a class="btn btn-danger pull-right" href="checkout.php"><i class="fa fa-check"></i> Proceed to checkout</a>
              </div>
            </div>
            <?php
					}else{
						echo '<div class="panel panel-smart text-danger text-center" style="min-height:250px;"><div class="row">
                	<div class="col-sm-offset-3 col-sm-6"><div role="alert" class="alert alert-danger alert-dismissible fade in text-center">
      <p>Your cart is currently empty!</p><br />
<br />

	  <p><a href="index.php" class="btn btn-warning">Click Here to Home Page</a></p>
      
    </div></div></div></div></div>';
					}
					?>
            </form>
            
          </div>
        </div>
      </div>
    </div>
  </section>
    <!-- service section -->
 
  
 <?php
include('includes/footer.php');
?>