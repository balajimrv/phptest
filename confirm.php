 <?php
include('includes/header.php');
?>
  
  <div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul>
            <li class="home"> <a title="Go to Home Page" href="index.php">Home</a><span>&raquo;</span></li>
            <li><strong>User Account Activation</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumbs End --> 
  
  <!--Container -->
  <div class="main-container col1-layout">
    <div class="container">
      <div class="row">
        <div class="col-main col-sm-12 col-xs-12">
          <div class="shop-inner">
          <div class="page-title">
              <h2 class="tb-border">User Account Activation</h2>
            </div>
           
            <div class="col-sm-12">
            	<p style="padding-bottom:40px;">
                <?php
				$errMsg='';
				$uId = base64_decode($_REQUEST['uid']);
				$sqlQry = "SELECT cStatus FROM `".TBL_USERS."` WHERE intUserId=".$uId." AND vCaptcha='".$_REQUEST['code']."'";
				$resQry = @mysql_query($sqlQry);
				$numRow = @mysql_num_rows($resQry);
				$rowQry = @mysql_fetch_object($resQry);
				if($numRow !=0){
					if($rowQry->cStatus == 'N'){
						$sqlUpd = "UPDATE `".TBL_USERS."` SET `cStatus` = 'Y' WHERE `intUserId` =".$uId."";
						$resUpd = mysql_query($sqlUpd);
						$errMsg = 'Your account has been activated. You will be redirected to the Login Page shortly...';
					}elseif($rowQry->cStatus == 'Y'){
						$errMsg = 'Your account activated already.';
					}
				}else{
					$errMsg = 'Invalied URL.';
				}
				
				if($errMsg !=''){ ?>
					<div align="center" style="color:#FF0000;"><strong><?php echo $errMsg; ?></strong></div>
					<meta http-equiv=Refresh content=2;url=login.php>
				<?php
				}
			?>
                </p>
            </div>            
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container End --> 
  <?php
include('includes/footer.php');
?>