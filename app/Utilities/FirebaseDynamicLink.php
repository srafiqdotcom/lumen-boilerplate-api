<?php

namespace App\Utilities;

class FirebaseDynamicLink
{
    protected $serverKey = 'AIzaSyBSIKNz9Kt8-RypCwTjvy0YC_funGFpUiY'; //get from firebase console
    protected $baseUrl = 'https://firebasedynamiclinks.googleapis.com/';
    protected $apiversion = 'v1/';
    protected $domainUrl = 'https://home.slashnext.cloud'; //get from firebase console
    protected $domain = 'home.slashnext.cloud'; //get from firebase console
    protected $androidPackageName = 'com.slashnext.home'; //old package name com.slashnext.mobileprotection
    protected $iosBundleId = 'com.slashnext.home';
    protected $iosAppStoreId = '1630075442';
    protected $iosFallbackLink = 'https://apps.apple.com/us/app/slashnext-mobile-security-home/id1630075442';
    protected $androidFallbackLink = 'https://googleplay.com/us';
    protected $enableForcedRedirect = true;
    protected $desktopFallbackLink =  "https://home-fallback.slashnext.cloud";
    protected $genericFallbackLink = "https://home-fallback.slashnext.cloud";
   // protected $genericFallbackLink = "http://54.212.79.194/ahad/cels/api/public/test1";
    protected $customerURLScheme = 'com.slashnext.home';
    protected $activationKey;
    protected $debug =true;
    protected $dyanmicShortLinkType = "familySharing";
    protected $dyanmicShortLink;


    public function __construct($activationKey)
    {

        if(!$activationKey) {
            throw new \Exception('Activation key is required');
        }
        $this->activationKey = $activationKey;
    }

    private function curlObject($requestType, $endPoint, $params = [])
    {
        $ch = curl_init();

        $httpHeaders = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];

        $url = $this->baseUrl.$this->apiversion.$endPoint;

        curl_setopt($ch, CURLOPT_URL, $url);

        if($requestType == 'POST')
        {
            $params = json_encode($params);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        else if($requestType == 'GET')
        {
            curl_setopt($ch, CURLOPT_HTTPGET, TRUE);

        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);
        $start = microtime(true);
        $this->log('Call to ' . $url . ': ');
        if($this->debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $curl_response = curl_exec($ch);

        $idx_http_code = 'http_code';
        $idx_error = 'error';

        $data =  json_decode($curl_response, true);

        $info = curl_getinfo($ch);

        if($info[$idx_http_code] == '400')
        {
            $erroMsg = '400 bad request';
            if(array_key_exists($idx_error, $data))
            {
                $erroMsg = $data[$idx_error]['message'];
            }
            throw new \Exception($erroMsg);
        }
        if($info[$idx_http_code] == '401')
        {
            $erroMsg = 'Invalid Credentials Provided';
            if(array_key_exists('status', $data))
            {
                $erroMsg = $data[$idx_error]['message'];
            }
            throw new \Exception($erroMsg);
        }

        if( floor( $info[$idx_http_code] / 100 )  >= 4)
        {
            throw new \Exception('Invalid Response Received from '.$url.': ' . $curl_response);
        }

        if(curl_error($ch))
        {
            throw new \Exception('API call to ' . $url . ' failed: ' . curl_error($ch));
        }

        $time = microtime(true) - $start;
        if($this->debug) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Got response: ' . $curl_response);

        if(curl_error($ch))
        {
            throw new \Exception("API call to $url failed: " . curl_error($ch));
        }

        if($data === null)
        {
            throw new \Exception('We were unable to decode the JSON response from the Firebase API:  ' .$endPoint);
        }
        return $data;
    }

    private function log($msg)
    {
        if($this->debug)
        {

            \Log::channel('deeplink')->info($msg );
           // Logger::WriteToEmailerLog('Firebase','CUSTOM',$msg);
        }
    }

    public function getShortLink()
    {
        $params = $this->jsonArrayForDynamicLink();

        $dynamicShortLink = $this->curlObject('POST', 'shortLinks?key='.$this->serverKey, $params);

        $this->dyanmicShortLink = $dynamicShortLink;

        return $dynamicShortLink;

    }

    private function jsonArrayForDynamicLink()
    {

        $this->dyanmicShortLink =  $this->customerURLScheme."://".$this->domain."/appkey/&key=".$this->activationKey."&type=".$this->dyanmicShortLinkType;

        return [
            "dynamicLinkInfo" => [
                "domainUriPrefix" => $this->domainUrl,
                "link" => $this->domainUrl."/appkey/?key=".$this->activationKey."&type=".$this->dyanmicShortLinkType,
                "androidInfo" => [
                    "androidPackageName" => $this->androidPackageName,
                   // "androidFallbackLink" =>$this->iosFallbackLink."?key=".$this->activationKey
                    "androidFallbackLink" => $this->genericFallbackLink."?shortlink=".$this->dyanmicShortLink."&platform=android"
                ],
                "iosInfo" => [
                    "iosBundleId" => $this->iosBundleId,
                    "iosAppStoreId" => $this->iosAppStoreId,
                    //"iosFallbackLink" => $this->iosFallbackLink."?key=".$this->activationKey
                    "iosFallbackLink" => $this->genericFallbackLink."?shortlink=".$this->dyanmicShortLink."&platform=ios",
                    "iosIpadBundleId" => $this->iosBundleId,
                    "iosIpadFallbackLink" => $this->genericFallbackLink."?shortlink=".$this->dyanmicShortLink."&platform=ipad",

                ],
                "navigationInfo" => [
                    "enableForcedRedirect" => $this->enableForcedRedirect
                ],
                "desktopInfo" => [
                    "desktopFallbackLink" => $this->genericFallbackLink."?shortlink=".$this->dyanmicShortLink."&platform=desktop"
                ]
            ]
        ];

    }

}

