<?php include('includes/header.php'); ?>

      <div class="page-header">
        <h1 style="color:#F30 !important; padding-top:20px;">Fresh Fruits</h1>
      </div>
      
      <div class="row">
      
      	  <?php
			$sqlQry = "SELECT * FROM ".TBL_PRODUCTS." WHERE cStatus = 'Y' ORDER BY product_id ASC";
			$resQry = mysql_query($sqlQry);
			$numQry = mysql_num_rows($resQry);
			while($rowQry = mysql_fetch_object($resQry)){
		 ?>
          <div class="col-sm-4 col-md-3">
            <div class="thumbnail">
                <img src="products/<?php echo $rowQry->product_image; ?>" alt="<?php echo $rowQry->product_name; ?>" style="width:100%" title="<?php echo $rowQry->product_name; ?>">
                <div class="caption text-center">
                  <h4 class="text-center"><?php echo $rowQry->product_name; ?></h4>
                  <p>Discount:<?php echo $rowQry->product_discount; ?>%</p>
                  <p style="font-size:18px;">
					<?php if($rowQry->product_discount == 0){
                    	$product_price = $rowQry->product_price;
                    }else{
                    	$product_price = $cnf->calculate_discount($rowQry->product_price, $rowQry->product_discount);
                    ?>
                    	<del>$<?php echo $rowQry->product_price; ?></del>
                    <?php } ?>&nbsp;&nbsp;&nbsp;
                    	<span style="color:#F00;">$<?php echo $cnf->displayAmount($product_price); ?></span>
                  </p>
                 
                  <form action="" method="post" name="frmProduct"  onsubmit="return valdateAdd(this)">
                  <input type="hidden" value="1" id="qty" name="qty"/>
                  <input type="hidden" value="<?php echo $rowQry->product_id; ?>" id="pid" name="pid"/>
                  <input type="hidden" value="<?php echo $product_price; ?>" id="product_price" name="product_price"/>
                  <button class="btn btn-success" type="submit" name="addcart" title="Add to Cart"><span><i class="fa fa-shopping-basket"></i> ADD TO CART</span></button>
                  </form>
                </div>
            </div>
          </div>
          <?php
			} 
		   ?>
      </div>        
    </div> <!-- /container -->
    
<?php

if(isset($_REQUEST["addcart"])) {
	$sqlCart = mysql_query("SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."' AND product_id=".$_REQUEST['pid']."");
	$rowCart = mysql_fetch_object($sqlCart);
	$resCartNo = mysql_num_rows($sqlCart);
	$qty = $rowCart->cart_qty;
	$qtyUpdate = $qty+$_REQUEST['qty'];
	
	$strPro = mysql_query("SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$_REQUEST['pid']."");
	$rowPro = mysql_fetch_object($strPro);	
		
	if($qtyUpdate <= $rowPro->product_stock){
		if($resCartNo==0){			
		$fieldarray = array(
			"product_id"=>$rowPro->product_id,
			"cart_qty"=>$_REQUEST['qty'],
			"product_price"=>$rowPro->product_price,
			"cart_session_id"=>$_SESSION['session_id'],
			"dtCreated"=>$cnf->datetime_format()
		  );
		$cnf->insert(TBL_CART,$fieldarray);			
		}else{
			
			//$qtyUpdate=$qty+$_REQUEST['qty'];
			$strSqlUpdate = "UPDATE ".TBL_CART." SET cart_qty=".$qtyUpdate." WHERE cart_session_id='".$_SESSION['session_id']."' AND ";
			$strSqlUpdate .= "product_id=".$_REQUEST['pid']."";
			$resCartUpdate = mysql_query($strSqlUpdate);
			//echo $strSqlUpdate;
		}
		//header("Location: cart.php");
		echo("<script>location.href = 'cart.php';</script>");
	}else{
		echo "<script type='text/javascript'>alert('Available stock (".$rowPro->product_stock.") only');</script>";
	}
}

include('includes/footer.php');

?>
	
