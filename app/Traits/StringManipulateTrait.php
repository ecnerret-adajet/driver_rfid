<?php

namespace App\Traits;

trait StringManipulateTrait {

    public function sentence_case($string) { 
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE); 
        $new_string = ''; 
        foreach ($sentences as $key => $sentence) { 
            $new_string .= ($key & 1) == 0? 
                ucfirst(strtolower(trim($sentence))) : 
                $sentence.' '; 
        } 
        return trim($new_string); 
    } 

    
}