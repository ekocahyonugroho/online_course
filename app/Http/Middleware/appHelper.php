<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 22/08/17
 * Time: 16:35
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Backend\Database_communication;
use Validator;

class appHelper {
    public function encryptString($String, $SaltPassword){
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encryptedMessage = openssl_encrypt($String, 'AES-256-CBC', $SaltPassword, 0, $iv);

        return $encryptedMessage;
    }

    public function decryptString($EncryptedString, $SaltPassword){
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $decryptedMessage = openssl_decrypt($EncryptedString, 'AES-256-CBC', $SaltPassword, 0, $iv);

        return $decryptedMessage;
    }

    public function getHTMLcontentsFromURL($url){
        $url = "https://" . $_SERVER['SERVER_NAME'] . "/".$url;
        return file_get_contents($url, false, $this->ignoreSSLPageFileGetContents());
    }

    public function ignoreSSLPageFileGetContents(){
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        return stream_context_create($arrContextOptions);
    }

    public function getClientIPaddress(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function getClientLocationBasedOnIPaddress($IP){
        if($IP != "127.0.0.1") {
            $json = file_get_contents("https://freegeoip.net/json/$IP", false, $this->ignoreSSLPageFileGetContents());
            $json = json_decode($json, true);
            $country = $json['country_name'];
            $region = $json['region_name'];
            $city = $json['city'];

            return $country . "-" . $region . "-" . $city;
        }else{
            return "localhost";
        }
    }

    public function getToken(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );
    }

    public function isUserAllowedToAccess(){
        $database = new Database_communication();

        if(session('idMember')){
            $idMember = session('idMember');
            $getUserMemberData = $database->getAccountDataByIdMember($idMember);

            if($getUserMemberData->count() > 0){
                $data = $getUserMemberData->first();

                if($data->IsActive == "1"){
                    if($data->ValidUntil != ""){
                        $dateNow = date('Y-m-d H:i:s');
                        $dateValid = date('Y-m-d H:i:s', strtotime($data->ValidUntil));

                        if($dateNow > $dateValid){
                            return FALSE;
                        }
                    }
                    return TRUE;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

?>