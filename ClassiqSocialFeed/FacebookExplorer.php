<?php

namespace ClassiqSocialFeed;


use Classiq\Models\SocialFeed;
use Classiq\Models\SocialPost;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Facebook;
use Pov\System\ApiResponse;


/**
 * Class FacebookExplorer
 * @package ClassiqSocialFeed
 */
class FacebookExplorer
{
    private static $_fb;
    /**
     * Convertit une date de l'api FB en DateTime
     * @param string $dateString
     * @return \DateTime
     */
    public static function date($dateString){
        $time=strtotime($dateString);
        $d=new \DateTime();
        $d->setTimestamp($time);
        return $d;
    }
    /**
     * Pour accéder à l'api FB
     * @return Facebook
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    private static function fb(){
        if(!self::$_fb){
            self::$_fb = new Facebook([
                'app_id' => KEYS::$FB_API_ID,
                'app_secret' => KEYS::$FB_API_SECRET,
                'default_graph_version' => 'v2.10',
                'default_access_token' => KEYS::$FB_API_TOKEN, // optional
            ]);
        }
        return self::$_fb;
    }
    /**
     * @param string $pageIdentifier
     * @return array Les données facebook d'api
     * @throws FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public static function getPageInfo($pageIdentifier){
        die("TODO");
        try {
            $response = self::fb()->get(
                "/$pageIdentifier?metadata=1&fields=metadata{type},id,name,about,description_html,display_subtext,general_info,cover,featured_video,picture.type(large),category,category_list,location,phone,emails,link,hours",
                KEYS::$FB_API_TOKEN
            );
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            throw $e;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw $e;
        }
        return $response->getDecodedBody();
    }

    /**
     * Fait appel au FB SDK
     * Récupère les posts d'une page facebook et les renvoie
     * @param string $pageIdentifier
     * @param string $fields Les champs (graph api) à récupérer séparés par une virgule.
     * @return mixed les posts
     * @throws FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public static function importPosts($pageIdentifier, $fields="id,type,name,link,message,picture,attachments,full_picture,created_time&orderby=created_time"){
        /** @var SocialFeed $bean */
        try {
            $response = self::fb()->get(
                "/$pageIdentifier/feed?fields=$fields",
                KEYS::$FB_API_TOKEN
            );
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            throw $e;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw $e;
        }
        if($response){
            return $response->getDecodedBody()["data"];
        }
        return null;
    }

    /**
     * @param string $pageIdentifier
     * @return array Les données facebook d'api page/events
     * @throws FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public static function getPageEvents($pageIdentifier){
        die("TODO");
        try {
            $response = self::fb()->get(
                "/$pageIdentifier/events",
                KEYS::$FB_API_TOKEN
            );
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            throw $e;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw $e;
        }
        return $response->getDecodedBody();
    }
    /**
     * @param string $postId
     * @return array Les données facebook d'api Post
     * @throws FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public static function getPostInfo($postId)
    {
        die("TODO...ou pas vu que getPageFeed fait déjà le job");
        try {
            $response = self::fb()->get(
                "/$postId?metadata=1&fields=metadata{type},id,type,name,link,message,picture,attachments,full_picture,created_time",
                KEYS::$FB_API_TOKEN
            );
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            throw $e;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw $e;
        }
        return $response->getDecodedBody();
    }
}