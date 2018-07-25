<?php
/**
 * Un flux de page facebook
 * @var Classiq\Models\JsonModels\ListItem $vv
 */
?>
<label>Quel est le nom de la page Facebook?</label>
<?=$vv->wysiwyg()->field("fbPage")
    ->string()
    ->onSavedRefreshListItem($vv)
    ->input();
?>
<label>Filtrer les posts par mot clé</label>
<?=$vv->wysiwyg()->field("keyword")
    ->string()
    ->onSavedRefreshListItem($vv)
    ->input();
?>
<label>Nombre de posts max à afficher</label>
<?=$vv->wysiwyg()->field("slice")
    ->string()
    ->setDefaultValue(5)
    ->onSavedRefreshListItem($vv)
    ->input("number");
?>
