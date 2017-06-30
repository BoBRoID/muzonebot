<?php
namespace common\helpers;

use common\models\SongTags;

class TagExtractor
{

    /**
     * @param $analise array
     * @return SongTags
     */
    public static function getInfo($analise){
        $songTags = new SongTags();

        if(array_key_exists('fileformat', $analise)){
            $songTags->format = $analise['fileformat'];
        }

        if(array_key_exists('tags', $analise)){
            $tags = self::getID3Tag($analise['tags']);
        }else{
            $tags = self::getID3TagComment($analise);
        }

        $songTags->load([$songTags->formName() => $tags]);

        if(array_key_exists('audio', $analise) && array_key_exists('bitrate', $analise['audio'])){
            $songTags->bitrate = ceil($analise['audio']['bitrate'] / 1000);
        }else if(array_key_exists('bitrate', $analise)){
            $songTags->bitrate = $analise['bitrate'];
        }

        if(array_key_exists('playtime_seconds', $analise)){
            $songTags->duration = $analise['playtime_seconds'];
        }

        $songTags->validate();

        return $songTags;
    }

    /**
     * @param $tags array
     * @return array
     */
    private static function getID3Tag($tags){
        if(array_key_exists('id3v2', $tags)){
            $tags = $tags['id3v2'];
        }else if(array_key_exists('id3v1', $tags)){
            $tags = $tags['id3v1'];
        }

        foreach($tags as &$tag){
            $tag = array_shift($tag);
        }

        if(array_key_exists('band', $tags)){
            $tags['artist'] = $tags['band'];
        }

        return $tags;
    }
    /**
     * @param $tags array
     * @return array
     */
    private static function getID3TagComment($tags){
        if(array_key_exists('id3v2', $tags)){
            $tags = $tags['id3v2']['comments'];
        }else if(array_key_exists('id3v1', $tags)){
            $tags = $tags['id3v1']['comments'];
        }

        if(!is_array($tags)){
            return [];
        }

        foreach((array)$tags as &$tag){
            if(!is_array($tag)){
                continue;
            }

            $tag = array_shift($tag);
        }

        if(array_key_exists('band', $tags)){
            $tags['artist'] = $tags['band'];
        }

        return $tags;
    }

}