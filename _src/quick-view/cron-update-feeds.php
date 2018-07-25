<?php
/**
 * Ce script met à jour les feeds depuis l'api Facebook
 */

use Classiq\Models\SocialFeed;

/** @var Socialfeed[] $feeds */
$feeds=db()->find("socialfeed","ORDER BY date_modified ASC"); //les plus vieux en premier
foreach ($feeds as $feed){
    if(the()->boot->getTime()<2000){
        $r=$feed->updateFromSocialApi();
        foreach ($r->errors as $err){
            echo $err."<br>";
        }
    }
}