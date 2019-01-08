<?php
/**
 * Ce script met à jour les feeds depuis l'api Facebook
 */

//et oui le sdk facebook peut renvoyer des notices...no comment :(
error_reporting(E_ALL ^ E_NOTICE);
use Classiq\Models\SocialFeed;

cq()->configPreventDbNotifications = true;
/** @var Socialfeed[] $feeds */
$feeds=db()->find("socialfeed","ORDER BY date_modified ASC"); //les plus vieux en premier
foreach ($feeds as $feed){
    if(the()->boot->getTime()<2000){
        $r=$feed->updateFromSocialApi();
        foreach ($r->errors as $err){
            echo $err."<br>";
        }
        if(!$r->errors){
            echo $feed->name." mis à jour<br>";
        }
    }
}
