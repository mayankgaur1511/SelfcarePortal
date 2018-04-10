<?php 

function get_array_var($var = 'ARKADINREGION', $KEY = NULL) {
    $r = false;
    switch ($var) {
        case 'ARKADINREGION':
            $r = array('APAC'=>'Asia Pacific','AFME'=>'Africa, Middle East','EU'=>'Europe','USCA'=>'US, Cananda & Latin America');

            if($KEY !== NULL) $r = $r[$KEY];
            break;
       case 'ARKADINBRIDGETYPE':
            $r = array('1'=>'Avaya 6200: WAPI','2'=>'Avaya 7000: WAPI','3'=>'Viper: BS-API','4'=>'WISE2: WAPI-T');

            if($KEY !== NULL) $r = $r[$KEY];
            break;
       

        case 'W2CONFERENCESETTINGS':
        $r = array('1'=>'Allow broadcasting','2'=>'Auto Recording on entry','3'=>'Allow Q&A Session ','4'=>'Block Dial Out','5'=>'Enable Roll Call','6'=>'Enable Recording','7'=>'Play Entry Tone','8'=>'Play Exit Tone','9'=>'Play name on exit','10'=>'Play name on entry','11'=>'Wait for Moderator');

        if($KEY !== NULL) $r = $r[$KEY];
        break;
        default;
        
    }
    return $r;
}

function get_convert_dateFormat($date='Now',$fORMAT="Y-m-d"){
     
    $timezone = new DateTimeZone("UTC");
    $date = new DateTime($date, $timezone);
    return $date->format($fORMAT);
       
   
}

function assoc_by($key, $array) {
    $new = array();
    foreach ($array as $v) {
        if (!array_key_exists($v->$key, $new))
            $new[$v->$key] = $v;
    }
    return $new;
}