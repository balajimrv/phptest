<?php ob_start(); @session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);

set_time_limit(500);
ini_set("max_execution_time",300);
ini_set('memory_limit', '-1');

//Create session id for user
$_SESSION['session_id'] = session_id();

$prefix = "phptest_";
define("TBL_CATEGORY", $prefix."category");
define("TBL_PRODUCTS", $prefix."product");
define("TBL_CART", $prefix."cart");
define("TBL_ORDER", $prefix."order");
define("TBL_ORDER_ITEM", $prefix."order_item");

define("SiteTitle","PHP Test - Online Store");

$curfile = basename($_SERVER['PHP_SELF']);
define("CURFILE",$curfile);

class DB_FUNCTINS {
	
    private $db;
    function __construct() {
        require_once 'db_connect.php';
        $this->db = new DB_CONNECT();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }
	
	
	function encrypt($plainText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);
		      			
		} 
		return bin2hex($encryptedText);
	}

	function decrypt($encryptedText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
		
	}
	//*********** Padding Function *********************

	 function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}

	//********** Hexadecimal to Binary function ********

	function hextobin($hexString) 
   	 { 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
		    {
				$binString=$packedString;
		    } 
        	    
		    else 
		    {
				$binString.=$packedString;
		    } 
        	    
		    $count+=2; 
        	} 
  	        return $binString; 
    	  } 
		  
	function curPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	function records_fetch($strSql) {
		$Sql = $strSql;
		$results = mysql_query($Sql);
		if(@mysql_fetch_object($results)) {
			$records_results = true; 
		}else{
			$records_results = false;
		}
		return $records_results;
	}

	function query_execute($strSql) {
		$Sql = $strSql;
		$results = mysql_query($Sql);
		return $results;
		mysql_free_result($results);
	}

	function insert($tblname,$array){
	
	if(!is_array($array)){
		die("insert record failed, info must be an array");
	}
	
	$strSqlInsert = "INSERT INTO ".$tblname." (";
	for ($i=0; $i<count($array); $i++){
		$strSqlInsert .= key($array);
		if ($i < (count($array)-1)){
			$strSqlInsert .= ", ";
		}else $strSqlInsert .= ") ";
			next($array);
	}
		 reset($array);
		 $strSqlInsert .= "VALUES (";
		 for ($j=0; $j<count($array); $j++){
		 $strSqlInsert.= "'".current($array)."'";
		 if ($j < (count($array)-1)){
		 $strSqlInsert .= ", ";
		 } else
		 $strSqlInsert .= ") ";
		 next($array);
		}
		//echo $strSqlInsert;
		//mysql_query($strSqlInsert) or die("query failed ".mysql_error());
		$this->query_execute($strSqlInsert) or die("query failed ".mysql_error());
		return mysql_insert_id(); 
	}

	function update($tblname,$fieldarray,$fieldname,$value){
		if(!is_array($fieldarray)){ 
			die("update record failed, info must be an array"); 
		}
		$strSqlUpdate = "UPDATE `".$tblname."` SET "; 
		foreach($fieldarray as $key=>$val){
			$strSqlUpdate .= $key."='".$val."',";
		}
		$strSqlUpdate = substr_replace($strSqlUpdate ,"",-1);
		$strSqlUpdate .= ' WHERE '.$fieldname.'='.$value.'';
		//echo $strSqlUpdate;
		$this->query_execute($strSqlUpdate) or die("query failed ".mysql_error());
	}
	
	function datetime_format() {
		$strDateTime = date("Y-m-d H:i:s");
		return	$strDateTime;
	}
	
	function json($data){
		header("Content-Type: application/json; charset=UTF-8");
	   	return json_encode($data);
	}

			
	function date_format($dateval) {
		$year = substr($dateval,0,4); 
		$month = substr($dateval,5,2);		
		$day = substr($dateval,8,2);
		$hour = substr($dateval,12,4);
		//echo $month."/".$day."/".$year."&nbsp;".$hour;  
		echo $month."/".$day."/".$year;
	}
	
	function HideMsg() {
		$strString  = "<script type='text/javascript' language='javascript'>";
		$strString .= "setTimeout('hide_msg();',5000);";
		$strString .= "</script>\n";
		return $strString;
	}
	
	function strreplace($strString) {
		$strsplval = array("‘", "’", "'");
		$strsplrepl= array("`", "`", "`");
		$str_String = str_replace($strsplval,$strsplrepl,$strString);
		return $str_String;
	}
	
	
	function convetdate($date){
		$split_date = explode('-',$date); 
		$strMonth = array("","Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
		if($split_date[1]==1){$Month=$strMonth[1];}
		else if($split_date[1]==2){$Month=$strMonth[2];}
		else if($split_date[1]==3){$Month=$strMonth[3];}
		else if($split_date[1]==4){$Month=$strMonth[4];}
		else if($split_date[1]==5){$Month=$strMonth[5];}
		else if($split_date[1]==6){$Month=$strMonth[6];}
		else if($split_date[1]==7){$Month=$strMonth[7];}
		else if($split_date[1]==8){$Month=$strMonth[8];}
		else if($split_date[1]==9){$Month=$strMonth[9];}
		else if($split_date[1]==10){$Month=$strMonth[10];}
		else if($split_date[1]==11){$Month=$strMonth[11];}
		else {$Month=$strMonth[12];}	
		$day = explode(' ',$split_date[2]);	
		echo $Month.'&nbsp;'.$day[0].',&nbsp;'.$split_date[0];
	}
	
	function random_string($type = 'alnum', $len = 8)
	{					
		switch($type)
		{
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
			
					switch ($type)
					{
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}
	
					$str = '';
					for ($i=0; $i < $len; $i++)
					{
						$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
					}
					return $str;
			  break;
			case 'unique' : return md5(uniqid(mt_rand()));
			  break;
		}
	}
	//Encode
	function encodeval($string) {
		$string = base64_encode($string);
		return $string;
	}
	
	//Decode
	function decodeval($string) {
		$string = base64_decode($string);
		return $string;
	}
	
	function displayAmount($amountval) {
		global $storeConfig;
		return $storeConfig['currency'] . number_format($amountval,2);
	}
	
	function calculate_discount($product_price, $discount_value){
		$discount = ($discount_value/100);
		$discount_price = ($discount * $product_price);
		$total_price = floor($product_price - $discount_price);
		//$total_price = number_format(round($total_price, 2), 2);
		return $total_price;
	}
}

?>