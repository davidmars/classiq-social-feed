<?php

namespace ClassiqSocialFeed;


use Pov\MVC\View;

/**
 * Class ClassiqSocialFeed
 * @package ClassiqSocialFeed
 */
class ClassiqSocialFeed
{
    /**
     * installe les vues par défaut de la lib.
     * Attention ces vues sont utiles pour tester mais nous vous encourrageons à créer les votres pour un meilleur rendu.
     */
    public static function installViews(){
        View::$possiblesPath[]= __DIR__ . "/../_src";
    }
}