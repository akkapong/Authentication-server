<?php
namespace Authens\Services;

use Authens\Repositories\AuthenRepositories;

class CryptoService extends AuthenRepositories
{
    const METHOD = 'aes-256-ctr';

    protected $accessToken;

    //==== Start: Define variable ====//
    
    //==== End: Define variable ====//


    //==== Start: Support method ====//
    //Method for encrypt message
    public function encrypt($message, $key, $encode=false)
    {
        $nonceSize  = openssl_cipher_iv_length(self::METHOD);
        $nonce      = openssl_random_pseudo_bytes($nonceSize);
        
        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        
        $final = $nonce.$ciphertext;
        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            $final = base64_encode($final);
        }

        return urlencode($final);

    }

    //Method for decrypt message
    public function decrypt($message, $key, $encoded=false)
    {
        $message = urldecode($message);
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
        
        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        
        return $plaintext;

    }

    //==== End: Support method ====//


    //==== Stat: Main method ====//
    //Method for process encrypt
    public function encryptProcess($appId, $message)
    {
        
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //get data
        $datas = $this->getDataById($appId);

        if (empty($datas)) {
            $outputs['success'] = false;
            $outputs['message'] = 'invalidApp';
            return $outputs;
        }

        
        $outputs['data'] = json_encode([
            'encrypted' => $this->encrypt($message, $datas->key64bit, true)
        ]);

        return $outputs;
    }

    //Method for process decrypt
    public function decryptProcess($appId, $encrypted)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //get data
        $datas = $this->getDataById($appId);

        if (empty($datas)) {
            $outputs['success'] = false;
            $outputs['message'] = 'invalidApp';
            return $outputs;
        }

        
        $outputs['data'] = json_encode([
            'plain_text' => $this->decrypt($encrypted, $datas->key64bit, true)
        ]);

        return $outputs;

    }

    
    //==== End: Main method ====//
}