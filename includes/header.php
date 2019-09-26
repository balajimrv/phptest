<?php require_once 'db_functions.php';
	$cnf = new DB_FUNCTINS();
	$pageName = $cnf->curPageName();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Balaji Rethinam">

    <title><?php echo SiteTitle; ?></title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css">
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  
  <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <h2 class="headtxt"><?php echo SiteTitle; ?></h2>
        </div>

      </div>
    </nav>

    <div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      
      <?php if($pageName != "success.php" && $pageName != "failure.php"){ ?>
      <div class="jumbotron" style="background-color:#FFF;">
      <?php include 'cartdetails.php'; ?>
      <div class="top-cart-contain">
              <div class="mini-cart">
                <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle"> <a href="cart.php">
                  <div class="cart-icon"><i class="fa fa-shopping-cart"></i></div>
                  <div class="shoppingcart-inner text-right"><strong><span class="cart-title">Cart</span> <span class="cart-total"><?php if($resCartno > 0) { echo $resCartno; } else { echo '0'; } ?> item(s) - <?php if($ch_SubTotal > 0){echo "Rs. ".$cnf->displayAmount($ch_SubTotal); } else { echo "(empty)"; }?></span></strong></div>
                  
                  
                  </a>
                  
                  </div>
                <div>
                  
                </div>
              </div>
            </div>
        <p class="text-right"><a href="cart.php" class="btn btn-info">View Cart</a></p>
      </div>
 <?php } ?>