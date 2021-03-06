<?php

if ( ! function_exists('getBrowser'))
{
	// Reference : http://php.net/manual/en/function.get-browser.php#101125
	function getBrowser($userAgentParam)
	{
	    $u_agent = $userAgentParam;
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'windows';
	    }
	   
	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    }
	    elseif(preg_match('/Firefox/i',$u_agent))
	    {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    }
	    elseif(preg_match('/Chrome/i',$u_agent))
	    {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    }
	    elseif(preg_match('/Safari/i',$u_agent))
	    {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    }
	    elseif(preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Opera';
	        $ub = "Opera";
	    }
	    elseif(preg_match('/Netscape/i',$u_agent))
	    {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }
	   
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }
	   
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }
	   
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
	   
	    return array(
	        'userAgent' => $u_agent,
	        'name'      => $bname,
	        'version'   => $version,
	        'platform'  => $platform,
	        'pattern'    => $pattern
	    );
	}
}

if ( ! function_exists('catatLog'))
{
	/*
	* @GET Parameter
	* $method = GET , POST , PUT , DELETE
	* $requestUri = mendapatkan link
	* $user_agent = mencatat user agent
	* $ip_address = mencacat ip address
	* $browser = mencatat browser dan didapatkan result melalui user agent
	* $platform = mencatat platform yang digunakan melalui user agent
	* $time = mendaptkan jam , menit dan detik saat melakukan fungsi ini.
	*/

	function catatLog($x = array())
	{
		$resultUa = getBrowser($x['user_agent']);

		$data = array( 
				'method' => @$x['method'],
				'requestUri' => @$x['requestUri'],
				'user_agent' => @$resultUa['userAgent'],
				'ip_address' => @$x['ip_address'],
				'browser' => @$resultUa['name'].' '.$resultUa['version'],
				'platform' => @$resultUa['platform'],
				'time' => date('H:i:s')
			);
		
		$file = FCPATH.'logs/'.date('d_m_Y').'/'.date('H').'.txt';
		
		$content = $data['method'];
		$content.= " , ".$data['requestUri'];
		$content.= " , ".$data['platform'];
		$content.= " , ".$data['user_agent'];
		$content.= " , ".$data['ip_address'];
		$content.= " , ".$data['browser'];
		$content.= " , ".$data['time'].PHP_EOL;

		// is directory created?
		if ( ! is_dir(FCPATH.'logs/'.date('d_m_Y')))
		{
			mkdir(FCPATH.'logs/'.date('d_m_Y'));
			@chmod ( FCPATH.'logs/'.date('d_m_Y') , 0754);
		}

		// is file created?
		if ( ! file_exists(FCPATH.'logs/'.date('d_m_Y').'/'.date('H').'.txt'))
		{	
			fopen(FCPATH.'logs/'.date('d_m_Y').'/'.date('H').'.txt' , 'w');
			@chmod ( FCPATH.'logs/'.date('d_m_Y').'/'.date('H').'.txt' , 0754);
		}

		// ok, put that content from variable data
		file_put_contents($file , $content , FILE_APPEND);
	}
}

if ( ! function_exists('trimLower'))
{
	function trimLower($string)
	{
		$string = trim($string);
		$string = strtolower($string);

		return $string;
	}
}

if ( ! function_exists('generate_string'))
{
	function generate_string($length) {
	    $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ"; // allowed chars in the password
	     if ($length == "" OR !is_numeric($length)){
	      $length = 8; 
	     }

	     $i = 0; 
	     $password = "";    
	     while ($i < $length) { 
	      $char = substr($possible, rand(0, strlen($possible)-1), 1);
	      if (!strstr($password, $char)) { 
	       $password .= $char;
	       $i++;
	       }
	      }
	     return $password;
	}
}

if ( ! function_exists('random_string'))
{
	function random_string($length) 
	{
	    $key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));

	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }

	    return $key;
	}
}

if ( ! function_exists('generate_key'))
{
	function generate_key()
	{
		$key1 = substr( md5(uniqid(rand(), true)),0,10);
		$key2 = generate_string('7');
		$key3 = strrev(strtotime( date('Y-m-d H:i:s')));
		return $key1.'-'.$key2.'-'.$key3;
	}
}

if ( ! function_exists('generate_image'))
{
	function generate_image($fileimage)
	{
		$key1 = substr( md5(uniqid(rand(), true)),0,15);
		$key2 = substr( md5($fileimage.time()),0,15);
		$key3 = random_string(15);
		// $x = explode('.',$fileimage);
		// $ext = count($x)-1;

		return $key1.'-'.$key2.'-'.$key3;
	}
}

if ( ! function_exists('validEmail'))
{
	function validEmail($string)
    {
        $string = $this->trimLower($string);
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($string, '@') === false && strpos($string, '.') === false) {
            return false;
        }
        if (!preg_match($chars, $string)) {
            return false;
        }
        return $string;
    }
}

if ( ! function_exists('ampm_to_24'))
{
	function ampm_to_24($time)
	{
		/*
		$x[0] = waktu
		$x[1] = am/pm
				
		$t[0] = jam
		$t[1] = menit
		*/
		$x = explode(' ',$time);
		$t = explode(':' , $x[0]);

		$outut = null;

		switch($x[1])
		{
			// 00 malam - 12 siang
			case 'am':
				switch($t[0])
				{
					case '01': $output = $t[0].':'.$t[1]; break;
					case '02': $output = $t[0].':'.$t[1]; break;
					case '03': $output = $t[0].':'.$t[1]; break;
					case '04': $output = $t[0].':'.$t[1]; break;
					case '05': $output = $t[0].':'.$t[1]; break;
					case '06': $output = $t[0].':'.$t[1]; break;
					case '07': $output = $t[0].':'.$t[1]; break;
					case '08': $output = $t[0].':'.$t[1]; break;
					case '09': $output = $t[0].':'.$t[1]; break;
					case '10': $output = $t[0].':'.$t[1]; break;
					case '11': $output = $t[0].':'.$t[1]; break;
					case '12': $output = $t[0].':'.$t[1]; break;
				}
			break;

			// 12 siang - 00 malam
			case 'pm':
				switch($t[0])
				{
					case '01': $output = '13:'.$t[1]; break;
					case '02': $output = '14:'.$t[1]; break;
					case '03': $output = '15:'.$t[1]; break;
					case '04': $output = '16:'.$t[1]; break;
					case '05': $output = '17:'.$t[1]; break;
					case '06': $output = '18:'.$t[1]; break;
					case '07': $output = '19:'.$t[1]; break;
					case '08': $output = '20:'.$t[1]; break;
					case '09': $output = '21:'.$t[1]; break;
					case '10': $output = '22:'.$t[1]; break;
					case '11': $output = '23:'.$t[1]; break;
					case '12': $output = '00:'.$t[1]; break;
				}
			break;
		}

		return $output;
	}
}

if ( ! function_exists('textToCenter'))
{
	function textToCenter($image, $text, $font, $size) {

	    $xi = ImageSX($image);
	    $yi = ImageSY($image);

	    $box = ImageTTFBBox($size, 0, $font, $text);
	    
	    $xr = abs(max($box[2], $box[4]));
	    $yr = abs(max($box[5], $box[7]));

	    $widthText = GetTextWidth($size, $font, $text) / 2;
	    $widthText = $widthText - ($widthText/2);
	    $totaly = intval(($yi + $yr) / 2);
	    $totaly = intval($totaly /  2);
	    $totaly2 = intval($totaly / 2);
	    $x = intval(($xi - $xr) / 2);
	    $x = $x<=$widthText?$widthText-$x:$x-$widthText;
	    // $y = intval(($yi + $yr) / 2);
	    $y = $totaly + $totaly2;

	    return array($x, $y);
	}
}
if ( ! function_exists('GetTextWidth')) {
	function GetTextWidth($fontSize, $font, $text){
	    $line_box = imagettfbbox ($fontSize, 0, $font, $text);
	    return ceil($line_box[0]+$line_box[2]); 
	}
}
if ( ! function_exists("justify")) {
	function justify($str_in, $desired_length, $char = '_') {
	    $return = '';
	    $str_in = trim( $str_in);
	    $desired_length = intval( $desired_length);
	    if( $desired_length <= 0)
	        return $str_in;

	    if( strlen( $str_in) > $desired_length) {
	        $str = wordwrap($str_in, $desired_length);
	        $str = explode("\n", $str);
	        $str_in = $str[0];
	    }
	    $words = explode( ' ', $str_in);
	    $num_words = count( $words);
	    if( $num_words == 1) {
	        $length = ($desired_length - strlen( $words[0])) / 2;
	        $return .= str_repeat( $char, floor( $length)) . $words[0] . str_repeat( $char, ceil( $length));
	    } else {
	        $word_length = strlen( implode( '', $words));
	        $num_words--; 
	        $spaces = floor( ($desired_length - $word_length) / $num_words);
	        $remainder = $desired_length - $word_length - ($num_words * $spaces);
	        $last = array_pop( $words);
	        foreach( $words as $word) {
	            $spaces_to_add = $spaces;
	            if( $remainder > 0) {
	                $spaces_to_add++;
	                $remainder--;
	            }
	            $return .= $word . str_repeat( $char, $spaces_to_add);
	        }
	        $return .= $last;
	    }
	    return $return;
	}
}

/* End of file goprayfunction_helper.php */
/* Location: ./application/helpers/goprayfunction_helper.php */