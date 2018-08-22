<?php

namespace Classiq\Models;
use ClassiqSocialFeed\FacebookExplorer;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Pov\System\ApiResponse;

/**
 * Class Socialfeed
 *
 * @package Classiq\Models
 *
 * @property string $sn Le social network
 * @property string $snid Identifiant unique sur le social network
 * @property string $snname Identifiant texte unique sur le social network
 * @property string $lastimportdate YYYY-MM-DD hh:mm:ss La date du dernier import
 * @property string $apidata Le json obtenu depuis l'api
 * @property string $apifeed Le json des posts obtenu depuis l'api
 *
 *
 */
class Socialfeed extends Classiqmodel
{
    static $icon="cq-money-euro";


    public function update()
    {
        parent::update();
        if($this->snname){
            $this->name=$this->snname;
        }
    }

    /**
     * Les posts du feed
     * @param int $start
     * @param int $slice
     * @param string $keyword Mot clé pour filtrer les posts
     * @return Socialpost[]
     */
    public function posts($start=0,$slice=50,$keyword=""){
        $sql="socialfeed_id='$this->id' ";
        if($keyword){
            $sql.=" AND data LIKE '%$keyword%'";
        }
        $sql.=" ORDER BY date DESC LIMIT $start,$slice";
        return db()->find(
            "socialpost",
            $sql
        );
    }

    /**
     * Renvoie un bean Socialfeed à partir de son nom de page et de son réseau social, le crée au besoin
     * @param string $pageIdentifier identifiant de page sur le SN
     * @param string $socialNetwork "facebook" par exemple
     * @return SocialFeed
     */
    public static function getByPageIdentifier($pageIdentifier,$socialNetwork){
        /** @var SocialFeed $bean */
        $bean=db()->findOne("socialfeed","sn='$socialNetwork' and snname='$pageIdentifier'");
        if(!$bean){
            $bean=db()->dispense("socialfeed");
            $bean->sn=$socialNetwork;
            $bean->snname=$pageIdentifier;
            $resp=$bean->updateFromSocialApi();
            if(!$resp->success){
                return null;
            }
        }
        return $bean;
    }

    /**
     * Met à jour le feed
     * @return ApiResponse
     */
    public function updateFromSocialApi()
    {
        $response=new ApiResponse();
        $posts=[];
        switch ($this->sn){
            case FACEBOOK:
                try {
                    $posts=FacebookExplorer::importPosts($this->snname);
                    if($posts){
                        $this->apifeed=json_encode($posts,JSON_PRETTY_PRINT);
                        foreach ($posts as $p){
                            $beanPost=Socialpost::createFromFbData($p,$this->unbox());
                        }
                    }
                } catch (FacebookResponseException $e) {
                    $response->addError($e->getMessage());
                } catch (FacebookSDKException $e) {
                    $response->addError($e->getMessage());
                }
                break;
            default:
                $response->addError("Réseau pas pris en charge ($this->sn)");

        }
        //enregistre si pas d'erreur
        if($response->success){
            db()->store($this->unbox());
        }
        return $response;
    }

}
