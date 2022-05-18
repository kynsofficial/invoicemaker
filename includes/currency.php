<?php

/*  ---------------------------------------------------------------------------
 * 	@package	: Currency
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */



function dropdown_currency($currency = '') {

	$currencies = array(

        'AED' => array('Arab Emirates Durham', 'AED'),
        'AUD' => array('Australian dollars', '$'),
        'BDT' => array('Bangladeshi TAKA', 'TK'),
        'BRL' => array('Brazilian real', 'R$'),
        'EGP' => array('British pound', '£'),
        'BTC' => array('Bitcoin', 'BTC'),
        'CNY' => array('Chinese Yuan', '¥'),
        'CAD' => array('Canadian dollar', '$'),
        'CLP' => array('Chilean peso', '$'),
        'CZK' => array('Czech koruna', 'CZK'),
        'HRK' => array('Croatian Kuna', 'kn'),
        'DKK' => array('Danish krone', 'kr'),
        'EUR' => array('Euro', '€'),
        'HUF' => array('Hungarian forint', 'ft'),
        'HKD' => array('Hong Kong dollar', 'HK$'),
        'INR' => array('Indian Rupee', 'INR'),
        'ILS' => array('Israeli shekel', 'ILS'),
        'IDR' => array('Indonesian rupiah', 'Rp'),
        'JPY' => array('Japanese yen', '¥'),
        'KRW' => array('Korean won', 'KRW'),
        'KES' => array('Kenyan shilling', 'Ksh'),
        'MXN' => array('Mexican peso', '$'),
        'RM' => array('Malaysian Ranggit', 'RM'),
        'NOK' => array('Norwegian krone', 'kr'),
        'NGN' => array('Nigerian naira', 'NGN'),
        'NZD' => array('New Zealand dollar', '$'),
        'PEN' => array('Peruvian sol', 'S/.'),
        'PKR' => array('Pakistani Rupee', 'Rs'),
        'PHP' => array('Philippine peso', 'PHP'),
        'RUB' => array('Russian ruble', 'RUB'),
        'RON' => array('Romanian leu', 'lei'),
        'SGD' => array('Singapore dollar', '$'),
        'ZAR' => array('South african rand**', 'R'),
        'SEK' => array('Swedish krona', 'kr'),
        'CHF' => array('Swiss franc', 'CHF'),
        'TRY' => array('Turkish lira', 'TRY'),
        'THB' => array('Thai baht', 'THB'),
        'UAH' => array('Ukrainian hryvna', 'UAH'),
        'USD' => array('United States Dollar', '$'),
        'VND' => array('Vietnamese dong', 'VND'),
        'XRP' => array('X Ripples', 'XRP'),

    );
    
    $data = '';
    
    foreach($currencies as $key => $value){
        
        if( $value[1] == $currency ){
            $data .= '<option value="'.$value[1].'" selected>'.$value[0].' ('.$value[1].')</option>';
        } else {
            $data .= '<option value="'.$value[1].'">'.$value[0].' ('.$value[1].')</option>';
        }
    }
    
    return $data;
}