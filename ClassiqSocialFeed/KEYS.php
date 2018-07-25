<?php

namespace ClassiqSocialFeed;

/**
 * Class KEYS contient les identifiants de connexion
 * @package ClassiqSocialFeed
 */
class KEYS
{
    /**
     * Token d'acces de l'application Facebook
     */
    public static $FB_API_TOKEN="xxx|yyy";
    public static $FB_API_SECRET="yyy";
    public static $FB_API_ID="xxx";

    /**
     * Définit les clés et le token à partir de là
     * @param string $apiId
     * @param string $apiSecret
     */
    public static function setFacebookKeys($apiId,$apiSecret){
        self::$FB_API_ID=$apiId;
        self::$FB_API_SECRET=$apiSecret;
        self::$FB_API_TOKEN=$apiId."|".$apiSecret;
    }

}