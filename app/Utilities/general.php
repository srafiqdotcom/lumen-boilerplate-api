<?php

use App\Utilities\ResponseHandler;

function CheckBoolValues($variable)
{

	if (empty($variable))
	{
		$response = 0;
	}
	else if ($variable === 'false')
	{
		$response = 0;
	}
	else if ($variable === 0)
	{
		$response = 0;
	}
	else if ($variable === "0")
	{
		$response = 0;
	}
	else if ($variable === 'true')
	{
		$response = 1;
	}
	else if ($variable === 1)
	{
		$response = 1;
	}
	else if ($variable === "1")
	{
		$response = 1;
	}
	else if ($variable === true)
	{
		$response = 1;
	}
	else if ($variable === false)
	{
		$response = 0;
	}
	return !empty($response) ? $response : 0;
}

function generateauthkey($username, $password, $apikey = '')
{
	$random = openssl_random_pseudo_bytes(20);

	$authkey = hash_hmac('sha256', urlencode($random.'-'.$username.'-'.$password), $apikey);

	return base64_encode($authkey);
}

function datetiemnow()
{
	return date('Y-m-d H:i:s');
}

function escencrypt($string,$salt)
{
	return base64_encode(openssl_encrypt($string,"AES-128-ECB",$salt));
}

function generateESCToken()
{
	$apikey = env('ESC_API_KEY');

	$separator = '|~#~|';

	$userstring = implode( $separator, [$apikey , time() ] );

	$encrypted_string = escencrypt($userstring, $apikey);

	return $encrypted_string;
}



function GenerateLinuxUUID()
{
	return exec('uuidgen');
}

function getNoOfDaysOfDateRange($startdate, $enddate)
{
	$start = new DateTime($startdate);

	$end = new DateTime($enddate);

	return (int) $end->diff($start)->format("%a");
}

function EscapeCliArg($string)
{
	$escaped = str_replace("'", "'\''", $string);

	return $escaped;
}

function generateMongoDate($date)
{
	$datestart = new \DateTime($date);

    return new \MongoDB\BSON\UTCDateTime($datestart->getTimestamp() * 1000);
}

function convertMongoDateToAmerican($data)
{
    return $data->toDateTime()->format("m-d-Y H:i:s");
}

function insertArrayAtPosition($array, $insert, $position)
{
    /*
    $array : The initial array i want to modify
    $insert : the new array i want to add, eg array('key' => 'value') or array('value')
    $position : the position where the new array will be inserted into. Please mind that arrays start at 0
    */
    return array_slice($array, 0, $position, TRUE) + $insert + array_slice($array, $position, NULL, TRUE);
}

function decryptData($string, $iv)
{
    if (!env('EP_ENCRYPTION_KEY')) {
        die("Encryption key is not set");
    }

    $decodedString = base64_decode($string);
    $plaintext = openssl_decrypt($decodedString, 'AES-128-CBC', env('EP_ENCRYPTION_KEY'), 1, $iv);

    return $plaintext;
}

function encryptData($string, $iv)
{
    if (!env('EP_ENCRYPTION_KEY')) {
        die("Encryption key is not set");
    }

    $ciphertext = openssl_encrypt($string, 'AES-128-CBC', env('EP_ENCRYPTION_KEY'), 1, $iv);
    $encodedString = base64_encode($ciphertext);

    return $encodedString;
}

function splitName($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}

function SendAndReturn($data, $url, $headers = array(), $returnraw = false)
{

    $httpheraders = array();

    $httpheraders[] = 'Content-Type: application/json';

    // automatically add AuthKey header is it exists
    if (empty($headers) && isset($_SESSION['AuthKey']))
    {
        $headers = array('AuthKey' => $_SESSION['AuthKey']);
    }

    foreach ($headers as $header => $value)
    {
        $httpheraders[] = $header.': '.$value;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheraders);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); //timeout in seconds

    $result = curl_exec($ch);
    if($result === false)
    {
        return ResponseHandler::ErrorResponse(curl_error($ch),\App\Utilities\Messages::$CURL_ERROR_CODE);
    }

    curl_close($ch);

    if ($returnraw)
    {
        return $result;
    }
    else
    {
        return json_decode($result, true);
    }
}

function intCodeRandom($length = 10)
{
    $intMin = (10 ** $length) / 10; // 100...
    $intMax = (10 ** $length) - 1;  // 999...

    $codeRandom = mt_rand($intMin, $intMax);

    return $codeRandom;
}

function isBase64Encoded($str)
{
    try {
        $decoded = base64_decode($str, true);
        return base64_encode($decoded) === $str;
    } catch (Exception $e) {
        // If exception is caught, then it is not a base64 encoded string
        return false;
    }
}
