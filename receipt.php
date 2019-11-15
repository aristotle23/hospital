<?php 

require "script/dbHandler.php";
require "script/functions.php";
require "script/escpos-php-development/autoload.php";
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

if(!isset($_REQUEST['pid'])){
	header("location:http://ashaka/index.php");
}




require_once "script/patient.php";
require_once "script/ini.php";

$db = new dbHandler();


// Enter the share name for your USB printer here
$connector = new WindowsPrintConnector('bixolon');
/* Print a "Hello world" receipt" */
$printer = new Printer($connector);

$patient = new patient($_REQUEST['pid']);
$receipt = $patient->receipt($_REQUEST['bill']);
?>
<html>
<head>
	<title>Billing Receipt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="vendor/jquery/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
  <script type="text/javascript" src="js/printThis.js"></script>
  <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
  
  <style>
  	.page-header{
		margin-top: 5px;
		margin-bottom:5px;
	}
	*{
		font-size: 7pt;
	}
	span{
		display:inline-block;
		margin-top: 5px;
	}
	
  </style>
</head>
<body>

<div class="container-fluid">
	
<div class="row" id="hprint">
	<div class="col-md-offset-3 col-md-6 " id="print-area">
    	<div class="page-header">
        <h5 style="margin-bottom: 0px;">Receipt</h5>
        </div>
        <div class="row">
        	<div class="col-md-3 col-xs-2" >
            	<span>Name:<span>
            </div>
            <div class="col-md-5 col-xs-10 hv">
            	<span><?php echo $receipt['pname'] ?></span>
            </div>
            <div class="col-md-2 col-xs-2">
            	<span>R_No:</span>
            </div>
            <div class="col-md-2 col-xs-10 hv">
            	<span><?php echo $receipt['rno'] ?></span>
            </div>
        </div>
        <div class="row">
        	<div class="col-md-3 col-xs-2">
            	<span>ID:</span>
            </div>
            <div class="col-md-6 col-xs-10">
            	<span><?php echo $receipt['id'] ?></span>
            </div>
            <div class="col-md-1 col-xs-2">
            	<span>Date:</span>
            </div>
            <div class="col-md-2 col-xs-10 hv">
            	<span><?php echo $receipt['date'] ?></span>
            </div>
        </div>
        <div class="row" style="margin-top:10px">
        	<div class="col-md-12">
            	<div class="table-responsive">
                  <table class="table serv-list">
                  <thead>
                  <tr>
                  <th>Services</th>
                  <th width="20%" >Amount</th>
                  </tr>
                  </thead>
                  <?php
				  	foreach($receipt['services'] as $service){
						print '<tr><td>'.$service['service'].'</td><td>'.$service['amount'].'</td></tr>';
					}
				  ?>
                  
                  </thead>
                  <tbody>
                  
                  </tbody>
                  </table>
               </div>
                
            </div>
           
 			<b>
            <div class="col-md-offset-6 col-md-2 col-xs-2">
            	<span>Total(N): </span>
            </div>
            <div class="col-md-4 col-xs-10 hv">
            	<span><?php echo $receipt['total'] ?></span>
            </div>
            <div class="col-md-offset-6 col-md-2 col-xs-2">
            	<span>Balance: </span>
            </div>
            <div class="col-md-4 hv col-xs-10">
            	<span><?php echo $receipt['balance'] ?></span>
            </div>
        	</b>
            <div class="col-md-12 col-xs-12" align="center">
            	<span><?php echo $receipt['complete'] ?></span>
            </div>
        </div>
        
        <div class="row" >
        	<div class="col-md-2 col-xs-2">
            	<span>Issuer:</span>
            </div>
            <div class="col-md-6 col-xs-10 hv">
            	<span><?php echo $receipt['issuer'] ?></span>
            </div>
            <div class="col-md-1 col-xs-2">
            	<span>Sign:</span>
            </div>
            <div class="col-md-2 hv col-xs-10">
            	<span>Management</span>
            </div>
            <div class="col-md-12 hv col-xs-12" align="center">
            	<span>Thanks for your patronage</span>
            </div>
        </div>
    </div>

</div>
    
</div>
<?php
/* Name of shop */
$logo = EscposImage::load("img/logo.jpg", false);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> graphics($logo);

$printer -> selectPrintMode();
$printer -> text("\nOpp. DSC Police Post Igbogidi Town UDU LGA, Delta State\nTEL: 08157712682, 0816630911\n");
$printer -> feed();

$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setLineSpacing(1);
//header
$printer -> selectPrintMode();
$printer -> setUnderline(false);
$printer -> text(str_pad("Name :",8).$receipt['pname']."\n");
$printer -> feed();
$printer -> text(str_pad("R_No :",8).$receipt['rno']."\n");
$printer -> feed();
$printer -> text(str_pad("ID :",8).$receipt['id']."\n");
$printer -> feed();
$printer -> text(str_pad("Date :",8).$receipt['date']."\n");
$printer -> feed();
//services
$printer -> text(str_pad("Services",30,' ').str_pad("Amount",12,' ',STR_PAD_LEFT));
$printer -> feed();
$printer -> text(str_pad("",42,'_')."\n");
$printer -> feed();
foreach($receipt['services'] as $service){
	$serv = new column($service['service'],$service['amount']);
	$printer -> text($serv."\n");
}
$printer -> text(str_pad("",42,"_")."\n");
$printer -> feed();
//total
$printer -> setEmphasis(true);
$printer -> text(str_pad("Total :",10).$receipt['total']."\n");
$printer -> feed();
$printer -> text(str_pad("Balance :",10).$receipt['balance']."\n");
$printer -> feed();
$printer -> setEmphasis(false);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text($receipt['complete']."\n");
$printer -> feed();
//footer
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text(str_pad("Issuer :",10).$receipt['issuer']."\n");
$printer -> feed();
$printer -> text(str_pad("Sign :",10)."Management"."\n");
$printer -> feed();
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Thanks for your patronage\n");
$printer -> feed();

$printer -> cut();
/* Close printer */
$printer -> close();
?>
<script>
/*$("#hprint").printThis({
	debug: true,               // show the iframe for debugging
    importCSS: true,            // import page CSS
    importStyle: true,         // import style tags
    printContainer: true,       // grab outer container as well as the contents of the selector
	removeScripts: false 
})*/

</script>
</body>
</html>