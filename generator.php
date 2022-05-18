<?php

/*  ---------------------------------------------------------------------------
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */


error_reporting(E_ALL);

/****************************************************************************
    included files
****************************************************************************/
include('includes/classess/invoicr.php');
include('includes/common-functions.php');
include('includes/thumbnail.php');

if(!isset($_POST['AccessFlag'])){
    header("location:index.php");
    return false;
}

/****************************************************************************
    header post
****************************************************************************/
$title = $_POST['title'];
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


/****************************************************************************
    To post
****************************************************************************/
$toBizName = $_POST['toBizName'];
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




/****************************************************************************
    items listing
****************************************************************************/
$proName  = $_POST['proName'];
$proDescArray = $_POST['proDesc'];
$amountArray = $_POST['amount'];
$vatArray = $_POST['vat'];
$priceArray = $_POST['price'];
$discountArray = $_POST['discount'];
$totalArray = $_POST['total'];


/****************************************************************************
    pdf generate
****************************************************************************/

//Set default date timezone
date_default_timezone_set('America/Los_Angeles');

//Create a new instance
$invoice = new invoicr("A4",$currency,"en");

//Set number format
$invoice->setNumberFormat(',',' ');

//Set tax format
$invoice->setTaxFormat($taxformat);

//Set tax format
$invoice->setDiscountFormat($discountFormat);

//Set your logo
if(isset($_FILES["image"]["name"]) && $_FILES["image"]["name"] != '')
{
    $imagePath =  'uploads/'.$_FILES["image"]["name"];
    moveUplaod('uploads');
    $invoice->setLogo($imagePath,180,100);
}

//Set theme color
$invoice->setColor($pdfColor);

//Set type
$invoice->setType($title);

//Set reference
$invoice->setReference($invocieNo);

//Set date
if($billingDate == ''){
    $billingDate = date('d.m.Y');
}
$invoice->setDate($billingDate);

//Set  due date
$invoice->setDue($dueDate);

//Set from
$invoice->setFrom(array($frmBizName,$frmAddress1,$frmAddress2,$frmPhone,$frmEmail,$frmAddInfo));

//Set to
$invoice->setTo(array($toBizName,$toAddress1,$toAddress2,$toPhone,$toEmail,$toAddInfo));

foreach( $proName as $key => $productName )
{
    $proDes =$proDescArray[$key];
    $amount =$amountArray[$key];
    $price =$priceArray[$key];
    if($_POST['applyDiscount'] == 'yes')
    {
        $discount =$discountArray[$key];
    }
    else
    {
        $discount = false;
    }
    if($_POST['applyTax'] == 'yes')
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

//Add totals
$invoice->addTotal("Sub Total",$subtotal);

//add taxes
if(isset($_POST['taxTitle']) || isset($_POST['taxValue']))
{
    $taxTitle  = $_POST['taxTitle'];
    $taxValueArray = $_POST['taxValue'];
    foreach( $taxTitle as $key => $title )
    {
        $taxValue = $taxValueArray[$key];
        $invoice->addTotal($title,$taxValue);
    }
}

$invoice->addTotal("Total",$totalBill,true);

$addBadge = $_POST['addBadge'];
if($addBadge != ''){
    //Add badge
    $invoice->addBadge($addBadge);
}

//Add signature
if($_POST['sig_name'] == '') {
    $sig_name = '';
}
else{
    $sig_name = $_POST['sig_name'];
}
$invoice->setSigName($sig_name);
if($_POST['sig_designation'] == '') {
    $designation = '';
}
else{
    $designation = $_POST['sig_designation'];
}
$invoice->setSigDesig($designation);

//Add title
if($_POST['extraNotes'] == '') {
    $noteTitle = '';
}
else{
    $noteTitle = $_POST['notesTitle'];
}
$invoice->addTitle($noteTitle);

//Add paragraph
if($_POST['extraNotes'] == '')
{
    $extraNotes = '';
}
else{
    $extraNotes = $_POST['extraNotes'];
}
$invoice->addParagraph($extraNotes);

//Set footernote
$invoice->setFooternote("https://ir.swiftspeed.org");

//Render
$invoice->render();