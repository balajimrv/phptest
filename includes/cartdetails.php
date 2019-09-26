<?php
	//Get cart details from cart table
	$strSqlCart = "SELECT * FROM ".TBL_CART." WHERE cart_session_id='".$_SESSION['session_id']."'";
	$resCart = mysql_query($strSqlCart);
	$resCartno = mysql_num_rows( $resCart );
	
	$ch_SubTotal = 0;
	while($rowsCart = mysql_fetch_object($resCart))	{
		$strSqlProduct = "SELECT * FROM ".TBL_PRODUCTS." WHERE product_id=".$rowsCart->product_id."";
		$resProduct = mysql_query($strSqlProduct);
		$resProductno = mysql_num_rows( $resProduct );
		$rowsProduct = mysql_fetch_object($resProduct);
		
		$ch_product_price = $cnf->calculate_discount($rowsProduct->product_price, $rowsProduct->product_discount);					
		$ch_Price = ($ch_product_price * $rowsCart->cart_qty); 
		$ch_SubTotal = $ch_SubTotal+$ch_Price; //Sub total
		//$grandTotal = $SubTotal; //Estimated total
	}
?>