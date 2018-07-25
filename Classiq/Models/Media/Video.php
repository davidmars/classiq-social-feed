<?php

namespace Classiq\Models\Media;



class Video extends Media
{
    public $type="video";
    /**
     * @return string
     */
    public function embedTag(){
        return "";
    }
}