<?php

namespace Classiq\Models;
use Classiq\Models\Media\Image;
use Classiq\Models\Media\Media;
use Classiq\Models\Media\Video;
use ClassiqSocialFeed\FacebookExplorer;
use DateTime;

/**
 * Class Socialpost
 *
 * @package Classiq\Models
 *
 * @property string $sn Le social network
 * @property string $snid Identifiant unique sur le social network
 * @property string $data Le json obtenu depuis l'api
 * @property string $posttype type de post
 * @property string $date date du post
 *
 * @property Socialfeed $socialfeed Le social feed associé
 * @property int $socialfeed_id id du social feed associé
 *
 *
 */
class SocialPost extends Classiqmodel
{
    static $icon="cq-money-euro";

    const POST_TYPE_PHOTO="photo";
    const POST_TYPE_VIDEO="video";
    const POST_TYPE_EVENT="event";
    const POST_TYPE_LINK="link";

    /**
     * @return array Le json obtenu depuis l'api
     */
    public function data(){
        if(!$this->_data){
            $this->_data=json_decode($this->data);
            $this->_data=utils()->array->fromObject($this->_data);
        }
        return $this->_data;
    }

    /**
     * @var array cache pour la methode data()
     */
    private $_data;

    /**
     * Image principale
     * @return string
     */
    public function full_picture(){
        $data=$this->data();
        if(isset($data["full_picture"])){
            return $data["full_picture"];
        }
        return "";
    }
    /**
     * lien du post sur le réseau social
     * @return string
     */
    public function networkLink(){
        $data=$this->data();
        if(isset($data["link"])){
            return $data["link"];
        }
        return "";
    }

    /**
     * Le titre du post
     * @return string
     */
    public function title(){
        $data=$this->data();
        if(isset($data["name"])){
            return $data["name"];
        }
        return "";
    }

    /**
     * Le message du post
     * @return string
     */
    public function message(){
        $data=$this->data();
        if(isset($data["message"])){
            return $data["message"];
        }
        return "";
    }

    /**
     * @return DateTime
     */
    public function date(){
        $data=$this->data();
        if(isset($data["created_time"])){
            return FacebookExplorer::date($data["created_time"]);
        }
        return "";
    }

    /**
     * @return Media[]
     */
    public function media(){
        $data=$this->data();
        $m=[];
        if(isset($data["attachments"])){
            foreach ($data["attachments"]["data"] as $att){
                switch ($att["type"]){
                    case "photo":
                        $image=new Image();
                        $image->src=$image->thumbnail=$att["media"]["image"]["src"];
                        $image->frgnNetwork=FACEBOOK;
                        $image->frgnId=$att["target"]["id"];
                        $m[]=$image;
                        break;
                    case "album":
                        foreach ($att["subattachments"]["data"] as $sub){
                            $image=new Image();
                            $image->src=$image->thumbnail=$sub["media"]["image"]["src"];
                            $image->frgnNetwork=FACEBOOK;
                            $image->frgnId=$sub["target"]["id"];
                            $m[]=$image;
                        }
                        break;
                    case "video_autoplay":
                    case "video_inline":
                        $vdo=new Video();
                        $vdo->thumbnail=$att["media"]["image"]["src"];
                        $vdo->frgnNetwork=FACEBOOK;
                        $vdo->frgnId=$att["target"]["id"];
                        $m[]=$vdo;
                        break;
                    case "share":
                    default:
                }
            }
        }
        return $m;
    }

    /**
     * Depuis des données d'API Facebook renvoie un modèle Socialpost et l'enregistre au passage
     * @param array $data les données du post issue de l'api facebook
     * @param Socialfeed $socialfeed Socialfeed associé
     * @return SocialPost|null
     */
    public static function createFromFbData($data, $socialfeed){
        if(isset($data["id"])){
           $snid=$data["id"];
        }else{
            return null;
        }
        /** @var SocialPost $post */
        $post=db()->findOne("socialpost","sn='".FACEBOOK."' AND snid='$snid' AND socialfeed_id='$socialfeed->id'");
        if(!$post){
            $post=db()->dispense("socialpost");
            $post->sn=FACEBOOK;
            $post->snid=$snid;
            $post->socialfeed=$socialfeed->unbox();
            $post->data=json_encode($data,JSON_PRETTY_PRINT);

            if(isset($data["type"])){
                switch ($data["type"]){
                    case "photo";
                        $post->posttype=self::POST_TYPE_PHOTO;
                        break;
                    case "video";
                        $post->posttype=self::POST_TYPE_VIDEO;
                        break;
                    case "link";
                        $post->posttype=self::POST_TYPE_LINK;
                        break;
                    default:
                        $post->posttype="fb_".$data["type"];
                }
            }

            if(isset($data["created_time"])){
                $post->date=FacebookExplorer::date($data["created_time"]);
            }

            if(isset($data["name"])){
                $post->name=$data["name"];
            }
            db()->store($post);
        }

        return $post;
    }
}
