<?php
/**
 * @var Classiq\Models\JsonModels\ListItem $vv
 *
 */



$fbPageName=$vv->getData("fbPage");
$keyword=$vv->getData("keyword");
$slice=$vv->getData("slice",5);

$feed=\Classiq\Models\SocialFeed::getByPageIdentifier($fbPageName,FACEBOOK);
$error="";

?>
<div <?= $vv->wysiwyg()->openConfigOnCreate()->attr() ?>>
    <div class="container">
        <div style="margin-bottom: 20px;border: 1px solid #ddd;padding: 15px;">
            Nom de la page Facebook: <?=$fbPageName?><br>
            Mot clé: <?=$keyword?><br>
            Nombre max: <?=$slice?><br>
        </div>
        <?if($feed):?>
        <?foreach ($feed->posts(0,$slice,$keyword) as $post):?>
            <div style="margin-bottom: 20px;border: 1px solid #ddd;padding: 15px;">
                <code style="font-size: 10px;background-color: #ddd;"><?=$post->uid()?></code>
                <code style="font-size: 10px;background-color: #ddd;"><?=$post->posttype?></code>
                <div>
                    <img style="max-width: 100%" src="<?=$post->full_picture()?>">
                </div>
                <div style="font-size: 10px;"><?=$post->date()->format("l d F Y H:i:s")?></div>
                <h2><?=$post->title()?></h2>
                <p><?=$post->message()?></p>
                <?if($post->networkLink()):?>
                <a style="font-size: 10px;" href="<?=$post->networkLink()?>" target="_blank"><?=$post->networkLink()?></a>
                <?endif?>
                <pre style="font-size: 10px;overflow: auto"><?=$post->data?></pre>
            </div>
        <?endforeach?>
        <?else:?>
        <div style="background-color: #f00;color:#fff; padding: 15px;">
            Le feed semble avoir un soucis
        </div>

        <?endif;?>
    </div>
</div>


