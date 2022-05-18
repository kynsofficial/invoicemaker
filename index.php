<?php

/*  ---------------------------------------------------------------------------
 * 	@package	: PDF Generator
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */


error_reporting(E_ALL);

/****************************************************************************
    included files
****************************************************************************/
include('includes/common-functions.php');
include('includes/currency.php');

if( isset($_GET['clear']) && $_GET['clear'] == 'cookie'){
    
    clearCookie('data_id');
    header("location:index.php");
    exit;
}

$data_id = UserCookieData('data_id');

if( file_exists('uploads/' . $data_id . '.json')){
    
    $data = @file_get_contents('uploads/' . $data_id . '.json');
    $data = json_decode( $data, true );

} else {
    
    $data = array(
        'taxformat' => '%',
        'applyTax' => 'yes',
        'applyDiscount' => 'yes',
        'currency' => '$',
        'discountFormat' => '%',
        'frmBizName' => '',
        'frmAddress1' => '',
        'frmAddress2' => '',
        'frmPhone' => '',
        'frmEmail' => '',
        'frmAddInfo' => '',
        'noteTitle' => 'Extra Notes',
        'extraNotes' => '',
        'pdfColor' => '#f7540e',
        'addBadge' => 'Original',
        'sig_name' => '',
        'sig_designation' => '',
        'haveImg' => ''
    );
    
}

extract($data);

?>
<!DOCTYPE html>
<html>
<head lang="en">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SWIFT INVOICEMAKER- Invoices Generator software by SSU-TECHNOLOGY LIMITED</title>

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <link rel="icon" type="image/x-icon" href="favicon.png">

    <link href="css/colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/calender.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/colorpicker.js"></script>
    <script src="js/eye.js"></script>
    <script src="js/calender.js"></script>
    <script src="js/cookies.js"></script>

    <!--[if lt IE 9]>
        <script type="text/javascript" src="js/html5shiv.js"></script>
    <![endif]-->
	
    <script type="text/javascript">
        function OnSubmitForm()
        {
            if(document.pressed == 'send')
            {
                document.myform.action ="send.php";
            }
            else
            if(document.pressed == 'download')
            {
                document.myform.action ="generator.php";
            }
            return true;
        }
    </script>

</head>

<body>

    <header>
   <!-- google adsense Auto Ads -->
        <div class="container">
            <img class="ilogo" src="images/icon.png" alt=""/>
            <h1>
                Swift-Invoice maker 
                <small>Invoices Generator Software by <a href="https://swiftspeed.org/">Ssu-Technology Limited</a></small>
            </h1>
            <a class="logo" href="?clear=cookie"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Clear Input</a>
        </div>
    </header>

    <div class="container setting-panel">

        <div class="row">

            <div class="col-lg-12">
                <h2>General Settings</h2>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="taxformat" class="caption">Tax</label>
                    <select class="form-control" onchange="changeTaxFormat(this.value)" id="taxformat">
                        <option value="%"<?php if($taxformat == "%" && $applyTax == 'yes') echo ' selected';?>>% Tax</option>
                        <option value="<?php echo $currency;?>"<?php if($taxformat == $currency && $applyTax == 'yes') echo ' selected';?>>Flat Tax</option>
                        <option value="off"<?php if($applyTax == 'no') echo ' selected';?>>Off</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="currency" class="caption">Currency</label>
                    <select class="form-control" onchange="changeCurrency()" id="currency">
                        <?php echo dropdown_currency($currency);?>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="discountFormat" class="caption">Discount</label>
                    <select class="form-control" onchange="changeDiscountFormat(this.value)" id="discountFormat">
                        <option value="%"<?php if($discountFormat == "%" && $applyDiscount == 'yes') echo ' selected';?>>% Discount</option>
                        <option value="<?php echo $currency;?>"<?php if($currency == $discountFormat && $applyDiscount == 'yes') echo ' selected';?>>Flat Discount</option>
                        <option value="off"<?php if($applyDiscount == 'no') echo ' selected';?>>Off</option>
                    </select>
<!-- google adsense -->
                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>
                        
                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>
<!-- Invoicemaker-AdsUnit [previously link ad unit] -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6418924292871415"
     data-ad-slot="9669870642"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

                </div>
            </div>

        </div>

    </div>

    <div class="container" id="paper-wrapper">

        <form method="post" name="myform" id="invocie_form" enctype="multipart/form-data" onsubmit="return OnSubmitForm();">

            <div class="row">

                <div class="col-sm-4">
                    <div class="logo-panl">
                        <div class="form-group logo">
                            <label for="exampleInputFile" class="caption">Upload your logo</label>
                            <input type="file" name="image" id="uploadFile" class="img">
                            <p>Note: Maximum resolution should be 180px * 100px (width * height)</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3" id="imagePreview" style="background-image:url('<?php echo $haveImg;?>')"></div>
                
                <?php if( $haveImg != ''){
                    echo'<input type="hidden" name="haveImg" value="'.$haveImg.'">';
                }?>

                <div class="col-sm-2"></div>

                <div class="col-sm-3 pull-right">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>
                            <input type="text" class="form-control"  placeholder="Title of the file" name="title" value="Invoice">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></div>
                            <input type="text" class="form-control"  placeholder="Invoice #" name="invocieNo">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div>
                            <input type="text" class="form-control"  id="billingDate" placeholder="Billing Date" name="billingDate" autocomplete="off"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div>
                            <input type="text" class="form-control" id="dueDate" name="dueDate" placeholder="Due Date" autocomplete="off">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-6 cmp-pnl">
                    <div class="inner-cmp-pnl">
                        <h2>Bill From</h2>
                        <div class="form-group">
                            <label for="frmBizName" class="caption">Business Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="frmBizName" value="<?php echo $frmBizName;?>" required="required">
                        </div>
                        <div class="form-group">
                            <label for="frmAddress1" class="caption">Address Line 1</label>
                            <input type="text" class="form-control" name="frmAddress1" value="<?php echo $frmAddress1;?>">
                        </div>
                        <div class="form-group">
                            <label for="frmAddress2" class="caption">Address Line 2</label>
                            <input type="text" class="form-control" name="frmAddress2" value="<?php echo $frmAddress2;?>">
                        </div>
                        <div class="form-group">
                            <label for="frmPhone" class="caption">Phone#</label>
                            <input type="text" class="form-control" name="frmPhone" value="<?php echo $frmPhone;?>">
                        </div>
                        <div class="form-group">
                            <label for="frmEmail" class="caption">Email</label>
                            <input type="email" class="form-control" name="frmEmail" value="<?php echo $frmEmail;?>">
                        </div>
                        <div class="form-group">
                            <label for="frmaddress2" class="caption">Additional Info</label>
                            <textarea class="form-control" name="frmAddInfo" rows="4"><?php echo $frmAddInfo;?></textarea>
                        </div>
                    </div>
                </div>


               <div class="col-sm-6 cmp-pnl" style="border-left:solid 1px #f5f6f7;">
                    <div class="inner-cmp-pnl">
                        <h2>Bill To</h2>
                        <div class="form-group">
                            <label for="toBizName" class="caption">Business Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="toBizName" required="required">
                        </div>
                        <div class="form-group">
                            <label for="toAddress1" class="caption">Address Line 1</label>
                            <input type="text" class="form-control" name="toAddress1">
                        </div>
                        <div class="form-group">
                            <label for="toAddress2" class="caption">Address Line 2</label>
                            <input type="text" class="form-control" name="toAddress2">
                        </div>
                        <div class="form-group">
                            <label for="toPhone" class="caption">Phone#</label>
                            <input type="text" class="form-control" name="toPhone">
                        </div>
                        <div class="form-group">
                            <label for="toEmail" class="caption">Email</label>
                            <input type="email" class="form-control" name="toEmail">
                        </div>
                        <div class="form-group">
                            <label for="toAddInfo" class="caption">Additional Info</label>
                            <textarea class="form-control" name="toAddInfo" rows="4"></textarea>
                        </div>
                    </div>
                </div>

            </div>
<!-- google adsense -->
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>
<!-- Invoicemaker-AdsUnit [previously link ad unit] -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6418924292871415"
     data-ad-slot="9669870642"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
     <div id="item-pnl">

                <div class="row items-pnl-head">
                    <div class="col-sm-1 col" >ACTION</div>
                    <div class="col-sm-6 col extendable" style="text-align: left">PRODUCTS</div>
                    <div class="col-sm-1 col" >QUANTITY</div>
                    <div class="col-sm-1 col">PRICE</div>
                    <div class="col-sm-1 col taxCol"<?php if($applyTax == 'no') echo ' style="display:none"';?>>TAX</div>
                    <div class="col-sm-1 col disCol"<?php if($applyDiscount == 'no') echo ' style="display:none"';?>>DISCOUNT</div>
                    <div class="col-sm-1 col" style="border-right:0">TOTAL</div>
                </div>

                <div class="row items-pnl-body" id="item-row">
                    <div class="col-sm-1 col" >
                        <p>
                            <button type="button" class="btn btn-success" aria-label="Left Align" data-toggle="tooltip" data-placement="top" title="Add more" id="add">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                        </p>
                    </div>
                    <div class="col-sm-6 col extendable ">
                        <input type="text" class="form-control firstCol req" name="proName[]" placeholder="Title">
                        <textarea class="form-control"  style="margin-top: 5px" name="proDesc[]" placeholder="Description"></textarea>
                    </div>
                    <div class="col-sm-1 col" >
                        <input type="text" class="form-control req amnt" value="1" name="amount[]"  id="amount-0" onkeypress="return isNumber(event)" onkeyup="calTotal('0'), calSubtotal()" autocomplete="off">
                    </div>
                    <div class="col-sm-1 col">
                        <div class="input-group">
                            <div class="input-group-addon currenty"><?php echo $currency;?></div>
                            <input type="text" class="form-control req prc"  name="price[]" id="price-0" onkeypress="return isNumber(event)" onkeyup="calTotal('0'), calSubtotal()"  autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-1 col taxCol"<?php if($applyTax == 'no') echo' style="display:none"';?>>
                        <div class="input-group">
                             <?php if($taxformat == $currency) echo'<div class="input-group-addon currenty new-aadon-tax">'.$currency.'</div>';?>
                            <input type="text" class="form-control vat" name="vat[]" id="vat-0" onkeypress="return isNumber(event)"   onkeyup="calTotal('0'), calSubtotal()" autocomplete="off">
                            <?php if($taxformat == '%') echo'<div class="input-group-addon default-addon-tax">%</div>';?>
                        </div>
                    </div>
                    <div class="col-sm-1 col disCol"<?php if($applyDiscount == 'no') echo' style="display:none"';?>">
                        <div class="input-group">
                             
                            <?php if($discountFormat == $currency) echo'<div class="input-group-addon currenty new-aadon">'.$currency.'</div>';?>
                            <input type="text" class="form-control discount"   name="discount[]" onkeypress="return isNumber(event)"  id="discount-0" onkeyup="calTotal('0'), calSubtotal()" autocomplete="off">
                            <?php if($discountFormat == '%') echo'<div class="input-group-addon  default-addon">%</div>';?>
                        </div>
                    </div>
                    <div class="col-sm-1 col">
                        <p><span class="currenty"><?php echo $currency;?></span> <span  class='ttlText' id="result-0">0</span></p>
                        <input type="hidden" class="ttInput"  name="total[]" id="total-0" value="0">
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-6 col-sm-offset-6 col-md-4 col-md-offset-8" id="tax-row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success" aria-label="Left Align" data-toggle="tooltip" data-placement="top" title="Add Taxes, Shipping, Handling or Other Fees" id="addTax">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="col-xs-5">
                        <h1 class="subtotalCap">Sub Total</h1>
                    </div>
                    <div class="col-xs-5">
                        <input type="hidden" value="0" id="subTotalInput" name="subtotal" >
                        <h1 class="subtotalCap">
                            <span class="currenty lightMode"><?php echo $currency;?></span>
                            <span id="subTotal" class="lightMode">0</span>
                        </h1>
                    </div>
                </div>
                <div class="col-sm-6 col-sm-offset-6 col-md-4 col-md-offset-8">
                    <div class="totalbill-row">
                        <div class="col-xs-5 col-sm-offset-2" >
                            <h1>Total : </h1>
                        </div>
                        <div class="col-xs-5" >
                            <h1><span class="currenty"><?php echo $currency;?></span> <span id="totalBill">0</span></h1>
                            <input type="hidden" value="0" name="totalBill" id="totalBillInput">
                        </div>
                    </div>
                </div>
            </div>

			<div style="height: 40px;"></div>

            <div class="row">
                <div class="col-md-12 cmp-pnl">
                    <div class="inner-cmp-pnl">
                        <input type="text" class="form-control" value="<?php echo $noteTitle;?>" name="notesTitle" placeholder="Extra Notes">
                        <div class="form-group">
                            <textarea class="form-control" name="extraNotes" rows="4" placeholder="Use this space to add some more text e.g. Terms & Conditions or Bank Details etc etc"><?php echo $extraNotes;?></textarea>
                        </div>
                    </div>
                </div>
            </div>
			
            <div class="row">

                <div class="col-sm-4 cmp-pnl">
                    <div class="inner-cmp-pnl">
                        <h2>Select a Color</h2>
                        <div id="colorSelector"><div style="background-color: <?php echo $pdfColor;?>"></div></div>
                        <input type="hidden" class="form-control" name="pdfColor" id="pdfColor" value="<?php echo $pdfColor;?>">
                        <small>Click on the color box and select a color of the invoice.</small>
                    </div>
                </div>

                <div class="col-sm-4 cmp-pnl">
                    <h2>Label / Stamp</h2>
                    <input type="text" class="form-control" value="<?php echo $addBadge;?>" name="addBadge">
                </div>

                <div class="col-sm-4 cmp-pnl">
                    <h2>Signature</h2>
                    <input type="text" class="form-control" placeholder="Name" name="sig_name" value="<?php echo $sig_name;?>">
                    <input type="text" class="form-control" placeholder="Designation" name="sig_designation" value="<?php echo $sig_designation;?>">
                    <small>Leave blank to hide or disable the Signatures on invoice.</small>
                </div>

            </div>

			<div style="height: 40px;"></div>
			
            <div class="row btns-row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-default sub-btn" onclick="document.pressed=this.value" value="download">Generate & Download Invoice</button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <button type="submit" name="sendEmail" class="btn btn-default sub-btn" onclick="document.pressed=this.value" value="send">Send Invoice to Client</button>
                    </div>
                </div>
            </div>

<!-- google adsense -->

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>
<!-- Invoicemaker-AdsUnit [previously link ad unit] -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6418924292871415"
     data-ad-slot="9669870642"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6418924292871415"
     crossorigin="anonymous"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-139553597-11"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-139553597-11');
</script>
    </script>

            <input type="hidden" value="0" id="taxCounter" >
            <input type="hidden" value="0" name="counter" id="counter">
            <input type="hidden" value="<?php echo $currency;?>" name="currency" id="currencyInput" >
            <input type="hidden" value="<?php echo $taxformat;?>" name="taxformat" id="taxFormatInput">
            <input type="hidden" value="<?php echo $discountFormat;?>" name="discountFormat" id="DisFormatInput">
            <input type="hidden" value="<?php echo $applyTax;?>" name="applyTax" id="applyTaxInput">
            <input type="hidden" value="<?php echo $applyDiscount;?>" name="applyDiscount" id="applyDiscount">
            <input type="hidden" value="true"  name="AccessFlag">

        </form>

    </div>
    
    <div class="cookie-bar"<?php if( isset($_COOKIE['data']) && $_COOKIE['data'] != '') echo ' style="display:none"';?>>
    
        <div class="pull-left">This website uses cookies to ensure you get the best experience on our website. <a href="https://swiftspeed.org/privacy-policy-term-of-usage/">Learn more</a></div>
        
        <div class="pull-right">
            <button type="button" class="btn btn-default" id="cookie-btn" data-id="decline">Decline</button>
            <button type="button" class="btn btn-success" id="cookie-btn" data-id="allow">Allow Cookies</button>
        </div>
        
        <div class="clearfix"></div>
    
    </div>
    
    <div class="cookie-bar-open">
        
        <img src="images/cookie.png" alt="">
        
    </div>

    <footer>
        <div class="container">
            <p class="pull-left">&copy 2019-2021 ALL RIGHTS RESERVED.</p>
            <p class="pull-right">DEVELOPED BY <a href="" target="_blank">SSU-TECHNOLOGY LIMITED</a></p>
        </div>
    </footer>

    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $('#colorSelector').ColorPicker({
            color: '#f7540e',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#colorSelector div').css('backgroundColor', '#' + hex);
                $('#pdfColor').val('#' + hex);
            }
        });
    </script>
    <script>
        $('#invocie_form').validate({
            errorPlacement: function(error,element) {
                return true;
            }
        });
        jQuery.validator.addClassRules('req', {
            required: true
        });
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 46 || charCode > 57)) {
                return false;
            }
            return true;
        }

</body>
</html>