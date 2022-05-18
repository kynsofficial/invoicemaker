<?php


/*  ---------------------------------------------------------------------------
 * 	@package	: Email Sending
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */


/****************************************************************************
    included files
****************************************************************************/
include('includes/common-functions.php');
include('includes/thumbnail.php');
include('includes/classess/invoicr.php');

$message = '';

if(!isset($_POST['AccessFlag'])){
    header("location:index.php");
    return false;
}

if(isset($_POST['sendEmail']) || isset($_POST['send']))
{

    /****************************************************************************
        header post
     ****************************************************************************/
    $titleVal = $_POST['title'];
    $invocieNo = $_POST['invocieNo'];
    $billingDate = $_POST['billingDate'];
    $dueDate = $_POST['dueDate'];

    /****************************************************************************
        From post
     ****************************************************************************/
    $frmBizName = $_POST['frmBizName'];
    $frmAddress1 = $_POST['frmAddress1'];
    $frmAddress2 = $_POST['frmAddress2'];
    $frmPhone = $_POST['frmPhone'];
    $frmEmail = $_POST['frmEmail'];
    $frmAddInfo = $_POST['frmAddInfo'];
    $toBizName = $_POST['toBizName'];

    /****************************************************************************
        To post
     ****************************************************************************/
    $toAddress1 = $_POST['toAddress1'];
    $toAddress2 = $_POST['toAddress2'];
    $toPhone = $_POST['toPhone'];
    $toEmail = $_POST['toEmail'];
    $toAddInfo = $_POST['toAddInfo'];

    /****************************************************************************
        Settings post
     ****************************************************************************/
    $currency = $_POST['currency'];
    $taxformat = $_POST['taxformat'];
    $discountFormat = $_POST['discountFormat'];
    $pdfColor  = $_POST['pdfColor'];
    $subtotal = $_POST['subtotal'];
    $totalBill = $_POST['totalBill'];
    $applyDiscount = $_POST['applyDiscount'];
    $applyTax = $_POST['applyTax'];
    $extraNotes = $_POST['extraNotes'];
    $addBadge = $_POST['addBadge'];
    $notesTitle = $_POST['notesTitle'];

    /****************************************************************************
    items listing
    ****************************************************************************/
    $proNameArray  = $_POST['proName'];
    $proDescArray = $_POST['proDesc'];
    $amountArray = $_POST['amount'];
    $vatArray = $_POST['vat'];
    $priceArray = $_POST['price'];
    $discountArray = $_POST['discount'];
    $totalArray = $_POST['total'];

    if(isset($_POST['send'])) {
        $proNameArray = explode("|", $proNameArray);
        $proDescArray = explode("|", $proDescArray);
        $amountArray = explode("|", $amountArray);
        $vatArray = explode("|", $vatArray);
        $priceArray = explode("|", $priceArray);
        $discountArray = explode("|", $discountArray);
        $totalArray = explode("|", $totalArray);
    }

    $proName = implode("|",$proNameArray);
    $proDesc = implode("|",$proDescArray);
    $amountVal = implode("|",$amountArray);
    $vatVal = implode("|",$vatArray);
    $priceVal = implode("|",$priceArray);
    $discountVal = implode("|",$discountArray);
    $totalVal = implode("|",$totalArray);

    /****************************************************************************
        pdf generate
     ****************************************************************************/

    //Set default date timezone
    date_default_timezone_set('America/Los_Angeles');
    //Create a new instance
    $invoice = new invoicr("A4",$currency,"en");
    //Set tax format
    $invoice->setTaxFormat($taxformat);
    //Set Discount format
    $invoice->setDiscountFormat($discountFormat);
    $image = '';
    if(isset($_POST['sendEmail']))
    {
        //Set your logo
        if (isset($_FILES["image"]["name"]) && $_FILES["image"]["name"] != '')
        {
            $imagePath = 'uploads/' . $_FILES["image"]["name"];
            moveUplaod('uploads');
            $invoice->setLogo($imagePath, 180, 100);
            //set the hidden input value
            $image = $_FILES["image"]["name"];
        }
    }else{
        $image = $_POST['image'];
        if($image != '')
        {
            $imagePath = 'uploads/' . $image;
            $invoice->setLogo($imagePath, 180, 100);
        }
    }
    //Set theme color
    $invoice->setColor($pdfColor);
    //Set type
    $invoice->setType($titleVal);
    //Set reference
    $invoice->setReference($invocieNo);
    //Set date
    $invoice->setDate($billingDate);
    //Set  due date
    $invoice->setDue($dueDate);
    //Set from
    $invoice->setFrom(array($frmBizName,$frmAddress1,$frmAddress2,$frmPhone,$frmEmail,$frmAddInfo));
    //Set to
    $invoice->setTo(array($toBizName,$toAddress1,$toAddress2,$toPhone,$toEmail,$toAddInfo));

    foreach( $proNameArray as $key => $productName )
    {
        $proDes =$proDescArray[$key];
        $amount =$amountArray[$key];
        $price =$priceArray[$key];
        if($applyDiscount == 'yes')
        {
            $discount =$discountArray[$key];
        }
        else
        {
            $discount = false;
        }
        if($applyTax == 'yes')
        {
            $vat = $vatArray[$key];
        }
        else
        {
            $vat = false;
        }
        $total =$totalArray[$key];
        $invoice->addItem($productName,$proDes,$amount,$vat,$price,$discount,$total);
    }

    //Add sub totals
    $invoice->addTotal("Sub Total",$subtotal);

    //add taxes
    if(isset($_POST['sendEmail']))
    {
        if(isset($_POST['taxTitle']) || isset($_POST['taxValue']))
        {
            $taxTitleArray  = $_POST['taxTitle'];
            $taxValueArray = $_POST['taxValue'];

            $taxTitle = implode("|",$taxTitleArray);
            $taxValues = implode("|",$taxValueArray);
        }else
        {
            $taxTitle = '';
            $taxValues = '';

        }
    }
    if(isset($_POST['send']))
    {
        $taxTitleArray  = explode("|", $_POST['taxTitle']);
        $taxValueArray  = explode("|", $_POST['taxValue']);
        $taxTitle = implode("|",$taxTitleArray);
        $taxValues = implode("|",$taxValueArray);
    }
    if(isset($taxTitle) && $taxTitle != '')
    {
        $taxTitleArray = array();
        $taxValueArray= array();
        foreach( $taxTitleArray as $key => $title )
        {
            $taxValue = $taxValueArray[$key];
            $invoice->addTotal($title,$taxValue);
        }
    }

    //Add total bill
    $invoice->addTotal("Total",$totalBill,TRUE);

    if($addBadge != ''){
        //Add badge
        $invoice->addBadge($addBadge);
    }

    //Add signature
    if($_POST['sig_name'] == '') {
        $sig_name = '';
    }else{
        $sig_name = $_POST['sig_name'];
    }
    $invoice->setSigName($sig_name);

    if($_POST['sig_designation'] == '') {
        $designation = '';
    }else{
        $designation = $_POST['sig_designation'];
    }
    $invoice->setSigDesig($designation);

    //Add title
    if($_POST['extraNotes'] == '') {
        $noteTitle = '';
    }else{
        $noteTitle = $notesTitle;
    }
    $invoice->addTitle($noteTitle);

    //Add paragraph
    if($_POST['extraNotes'] == '')
    {
        $extraNotes = '';
    }else{
        $extraNotes = $_POST['extraNotes'];
    }
    $invoice->addParagraph($extraNotes);

    //Set footer note
    $invoice->setFooternote("");

}
if(isset($_POST['send']))
{

    $to = $_POST["to"];
    $from = $_POST['from'];
    $nameVal = $_POST['name'];
     $subject = 'You\'ve received an invoice from '.$nameVal;
    $message = $_POST['mesg'];
    if(isset($_POST['sendToMe']))
    {
        $sendToMe = $_POST['sendToMe'];
    }else
    {
        $sendToMe = 'no';
    }

    $invoice->SendEmail($to,$from,$subject,$message,$sendToMe,$nameVal);
    $message = "Your invoice has just been sent to ". $to ."!";

}

?>

<!DOCTYPE html>
<html>
<head lang="en">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Swift-Invoice Maker - Invoices Generator Software by SSU-TECHNOLOGY LIMITED</title>

    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

    <link rel="icon" type="image/x-icon" href="favicon.png">

    <link href="css/colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/calender.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/colorpicker.js"></script>
    <script src="js/eye.js"></script>
    <script src="js/calender.js"></script>

    <!--[if lt IE 9]>
        <script type="text/javascript" src="js/html5shiv.js"></script>
    <![endif]-->

</head>
<body>

    <header>
        <div class="container">
			<img class="ilogo" src="images/icon.png" alt=""/>
            <h1>
                Swift-Invoice Maker
                <small>Invoices Generator Software by <a href="https://swiftspeed.org">Ssu-Technology Limited</a></small>
            </h1>
            <a class="logo" href="https://invoicemaker.swiftspeedtechnology.com"></a>
        </div>
    </header>

<!-- google adsense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>

    <form method="post" action="send.php" id="myform">
        <div class="container sending-panel">

            <div class="row">

                <div class="col-lg-12">

                    <h2>SEND INVOICE</h2>

                    <h4>Send a professionally crafted email with your invoice attached to anyone in the world, for free.</h4>

                    <?PHP
                    if($message !='')
                    {
                        ?>
                        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" style="font-size: 16px">&nbsp;</span><?PHP echo $message?></div>
                    <?PHP
                    }else
                    {
                        ?>
                        <div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-lock">&nbsp;</span>We do not collect, save, use, or spam your client's email address beyond sending this invoice.</div>
                    <?PHP
                    }
                    ?>

                </div>

                <div class="col-sm-6 emailForm-panel">

                    <h4>Sender information</h4>

                    <div class="form-group">
                        <label for="to" class="caption">What email address do you want to send this to?</label>
                        <input type="text" class="form-control email " name="to" id="to" onkeyup="changePreValue()" placeholder="email@example.com"  autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name" class="caption">What is the name of you or your company?</label>
                        <input type="text" class="form-control" value="My Company " name="name" id="name" onkeyup="changePreValue()" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="from" class="caption">What is your email address?</label>
                        <input type="email" class="form-control email" name="from" id="from" onkeyup="changePreValue()" autocomplete="off" placeholder="email@example.com">
                    </div>

                    <div class="form-group">
                        <label for="EmailMesg" class="caption">Custom Message</label>
                        <label for="mesg"></label><textarea class="form-control" name="mesg"  rows="4" id="mesg" onkeyup="changePreValue()"></textarea>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox"  value="yes" name="sendToMe"> <span class="caption" style="float:left; margin-top: 2px">Send a copy to me</span>
                        </label>
                    </div>

                    <!--pdf information start-->
                    <input type="hidden" name="title" value="<?PHP echo (isset($titleVal))?$titleVal:"" ?>">
                    <input type="hidden" name="invocieNo" value="<?PHP echo (isset($invocieNo))?$invocieNo:"" ?>">
                    <input type="hidden" name="billingDate" value="<?PHP echo (isset($billingDate))?$billingDate:"" ?>">
                    <input type="hidden" name="dueDate" value="<?PHP echo (isset($dueDate))?$dueDate:"" ?>">

                    <!--from info-->
                    <input type="hidden" name="frmBizName" value="<?PHP echo (isset($frmBizName))?$frmBizName:"" ?>">
                    <input type="hidden" name="frmAddress1" value="<?PHP echo (isset($frmAddress1))?$frmAddress1:"" ?>">
                    <input type="hidden" name="frmAddress2" value="<?PHP echo (isset($frmAddress2))?$frmAddress2:"" ?>">
                    <input type="hidden" name="frmPhone" value="<?PHP echo (isset($frmPhone))?$frmPhone:"" ?>">
                    <input type="hidden" name="frmEmail" value="<?PHP echo (isset($frmEmail))?$frmEmail:"" ?>">
                    <input type="hidden" name="frmAddInfo" value="<?PHP echo (isset($frmAddInfo))?$frmAddInfo:"" ?>">

                    <!--To info-->
                    <input type="hidden" name="toBizName" value="<?PHP echo (isset($toBizName))?$toBizName:"" ?>">
                    <input type="hidden" name="toAddress1" value="<?PHP echo (isset($toAddress1))?$toAddress1:"" ?>">
                    <input type="hidden" name="toAddress2" value="<?PHP echo (isset($toAddress2))?$toAddress2:"" ?>">
                    <input type="hidden" name="toPhone" value="<?PHP echo (isset($toPhone))?$toPhone:"" ?>">
                    <input type="hidden" name="toEmail" value="<?PHP echo (isset($toEmail))?$toEmail:"" ?>">
                    <input type="hidden" name="toAddInfo" value="<?PHP echo (isset($toAddInfo))?$toAddInfo:"" ?>">

                    <!--items list-->
                    <input type="hidden" name="proName" value="<?PHP echo (isset($proName))?$proName:"" ?>">
                    <input type="hidden" name="proDesc" value="<?PHP echo (isset($proDesc))?$proDesc:"" ?>">
                    <input type="hidden" name="amount" value="<?PHP echo (isset($amountVal))?$amountVal:"" ?>">
                    <input type="hidden" name="vat" value="<?PHP echo (isset($vatVal))?$vatVal:"" ?>">
                    <input type="hidden" name="price" value="<?PHP echo (isset($priceVal))?$priceVal:"" ?>">
                    <input type="hidden" name="discount" value="<?PHP echo (isset($discountVal))?$discountVal:"" ?>">
                    <input type="hidden" name="total" value="<?PHP echo (isset($totalVal))?$totalVal:"" ?>">

                    <!--others-->
                    <input type="hidden" name="currency" value="<?PHP echo (isset($currency))?$currency:"" ?>">
                    <input type="hidden" name="taxformat" value="<?PHP echo (isset($taxformat))?$taxformat:"" ?>">
                    <input type="hidden" name="discountFormat" value="<?PHP echo (isset($discountFormat))?$discountFormat:"" ?>">
                    <input type="hidden" name="pdfColor" value="<?PHP echo (isset($pdfColor))?$pdfColor:"" ?>">
                    <input type="hidden" name="subtotal" value="<?PHP echo (isset($subtotal))?$subtotal:"" ?>">
                    <input type="hidden" name="totalBill" value="<?PHP echo (isset($totalBill))?$totalBill:"" ?>">
                    <input type="hidden" name="applyDiscount" value="<?PHP echo (isset($applyDiscount))?$applyDiscount:"" ?>">
                    <input type="hidden" name="applyTax" value="<?PHP echo (isset($applyTax))?$applyTax:"" ?>">
                    <input type="hidden" name="extraNotes" value="<?PHP echo (isset($extraNotes))?$extraNotes:"" ?>">
                    <input type="hidden" name="taxTitle" value="<?PHP echo (isset($taxTitle))?$taxTitle:"" ?>">
                    <input type="hidden" name="taxValue" value="<?PHP echo (isset($taxValues))?$taxValues:"" ?>">
                    <input type="hidden" name="image" value="<?PHP echo (isset($image))?$image:"" ?>">
                    <input type="hidden" name="addBadge" value="<?PHP echo (isset($addBadge))?$addBadge:"" ?>">
                    <input type="hidden" name="notesTitle" value="<?PHP echo (isset($notesTitle))?$notesTitle:"" ?>">

                    <input type="hidden" name="sig_name" value="<?PHP echo (isset($sig_name))?$sig_name:"" ?>">
                    <input type="hidden" name="sig_designation" value="<?PHP echo (isset($designation))?$designation:"" ?>">


                    <input type="hidden" value="true"   name="AccessFlag">

                    <div class="form-group">
                        <label for="submit"></label>
                        <button type="submit" name="send" class="btn btn-default sub-btn">Send Email</button>
                    </div>

                </div>

                <div class="col-sm-6">

                    <h4>Preview</h4>

                    <div class="preview-panel">

                        <div class="form-group">
                            <label for="exampleInputEmail1" class="caption">To</label>
                            <p class="form-control-static"><span id="prevToEmail">email@example.com</span></p>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1" class="caption">From</label>
                            <p class="form-control-static"><span id="prevFrmEmail">email@example.com</span></p>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1" class="caption">Subject</label>
                            <p class="form-control-static">You've received an invoice <span id="prevName"></span></p>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1" class="caption">Message</label>
                            <div class="preview-msg-pnl">
                                <div class="preview-msg">
                                    <h2><span id="emailHeading">Swift-Invoice Generator</span></h2>
                                    <p>Greetings</p>
                                    <p>You've received an invoice from <span id="emailSub">A Customer</span></p>
                                    <p>You will find a PDF attached.</p>
                                    <p id="prevMesg"></p>
                                    <p><small><strong>SWIFT-INVOICE MAKER </strong> - Invoices generator software by <a href="https://swiftspeed.org" target="_blank">Ssu-Technology Limited</a></small></p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </form>

<!-- google adsense -->


    <footer>
        <div class="container">
            <p class="pull-left">&copy 2020 ALL RIGHTS RESERVED</p>
            <p class="pull-right">DEVELOPED BY <a href="https://swiftspeed.org" target="_blank">SSU-TECHNOLOGY LIMITED</a>.</p>
        </div>
    </footer>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script>
        $.validator.setDefaults({
            debug: false
        });
        $( "#myform" ).validate({
            rules: {
                to: {
                    required: true,
                    email: true
                },
                from: {
                    required: true,
                    email: true
                },
                name: "required"
            },
            messages: {
                to: "Please enter a valid email address.",
                from: "Please enter a valid email address.",
                name: "Please enter the name of you or your company."
            }
        });
    </script>

</body>
</html>