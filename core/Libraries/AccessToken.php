<?php
namespace Core\Libraries;

use Phalcon\DI;

class AccessToken
{
    protected $paths;

    public function __construct()
    {
        $configs = DI::getDefault()->get('config');
        $this->paths = $configs->path->toArray();
    }

    public function openKeyFile($filename, $mode='client_key')
    {
        $fp  = fopen($this->paths[$mode].$filename, "r");
        $key = fread($fp, 8192);

        fclose($fp);

        return $key;
    }

    // public function encryptString($plaintext, $publicKey)
    // {
    //     // $plaintext = "TEST123456778";
    //     // --- ENCRYPTION ---
    //     $finaltext = "";

    //     // $fp        = fopen ($this->paths[$mode].$filename, "r");
    //     // $publicKey = fread ($fp, 8192);

    //     // fclose($fp);
    //     $pk        = "";
    //     $pk        = openssl_get_publickey($publicKey);
        
        
    //     openssl_public_encrypt($plaintext, $finaltext, $pk);

        
    //     if (!empty($finaltext)) {
    //         openssl_free_key($pk);
    //     }

    //     return base64_encode($finaltext);
    // }

    public function encryptString($plaintext, $publicKey)
    {
        // --- ENCRYPTION ---
        $finaltext = "";
        $pk        = openssl_get_publickey($publicKey);
        
        $maxlength = 117;
        $finaltext    = '';
        while ($plaintext){ 
            $input     = substr($plaintext, 0, $maxlength);
            $plaintext = substr($plaintext, $maxlength);
            openssl_public_encrypt($input, $encrypted, $pk);
            $finaltext .= $encrypted;
        }

        if (!empty($finaltext)) {
            openssl_free_key($pk);
        }
        
        return base64_encode($finaltext);
    }

    // public function decryptString($ciphertextBase64, $privateKey)
    // {
    //     // //--- DECRYPTION ---
    //     $crypted    = base64_decode($ciphertextBase64);
        
    //     // $fp         = fopen ($this->paths[$mode].$filename,"r");
    //     // $privateKey = fread ($fp, 8192);
    //     // fclose($fp);
    //     $pk         = openssl_get_privatekey($privateKey);
    //     openssl_private_decrypt($crypted, $plaintext, $pk);
        
    //     return $plaintext;
    // }

    public function decryptString($ciphertextBase64, $privateKey)
    {
        // //--- DECRYPTION ---
        $crypted    = base64_decode($ciphertextBase64);
        $pk         = openssl_get_privatekey($privateKey);

        $maxlength = 256;
        $plaintext = '';
        while ($crypted) {
            $input = substr($crypted, 0, $maxlength);

            $crypted = substr($crypted, $maxlength);
            openssl_private_decrypt($input, $decrypted, $pk);

            $plaintext .= $decrypted;
        }
        return $plaintext;
    }

    
}
