<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class URLHelper{
    public static function has($subUrl){
        $url = explode("/", URL::current());
        for($i = 0; $i < 3; $i++){
            unset($url[$i]);
        }

        return in_array($subUrl, $url);
    }
}