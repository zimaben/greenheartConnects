<?php
namespace gh_connects\theme\classes;


// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class Condenser extends \GreenheartConnects {
    
    public function __construct( $text, $limit ){        

    }
    public static function limitCharacters( $text = 'string', $char = 145  ){
        $return = false;
        if(is_int($char) && is_string($text)){
            $chars = 0;
            $return = array();
            $word_array = explode(' ', trim($text));
            if(count($word_array) > 1 ){
                while( $chars < $char && count($word_array) > 0 ){
                    $word = array_shift($word_array);
                    $charlength = strlen($word);
                    array_push($return, $word);
                    $chars+= $charlength;
                }
                $return = join(' ', $return);
                if(count($word_array)) $return = $return.='...';
            } else {
                $return = trim($text);
            }    
        }
    return $return;
    }
    public static function limitCharactersNoEllipses( $text = 'string', $char = 145  ){
        $return = false;
        if(is_int($char) && is_string($text)){
            $chars = 0;
            $return = array();
            $word_array = explode(' ', trim($text));
            if(count($word_array) > 1 ){
                while( $chars < $char && count($word_array) > 0){
                    $word = array_shift($word_array);
                    $charlength = strlen($word);
                    array_push($return, $word);
                    $chars+= $charlength;
                }
                $return = join(' ', $return);
            } else {
                $return = trim($text);
            }
        }
    return $return;
    }
    public static function limitWords( $text = 'string', $words = 30  ){
        $return = false;
        if(is_int($words) && is_string($text)){
            $word = 0;
            $return = array();
            $word_array = explode(' ', trim($text));
            if( count($word_array) > 1 ){
                while( $word < $words && count($word_array) > 0 ){
                    $thisword = array_shift($word_array);
                    array_push($return, $thisword);
                    $word++;
                }
            
            $return = join(' ', $return);
            
            if(count($word_array)) $return = $return.='...';
            } else {
                $return = trim($text);
            }
        }
    return $return;
    }
    public static function limitWordsNoEllipses( $text = 'string', $words = 30  ){
        $return = false;
        if(is_int($words) && is_string($text)){
            $word = 0;
            $return = array();
            $word_array = explode(' ', trim($text));
            if(count($word_array) > 1 ){
                while( $word < $words && count($word_array) > 0 ){
                    $thisword = array_shift($word_array);
                    array_push($return, $thisword);
                    $word++;
                }
                $return = join(' ', $return);
            } else {
                $return = trim($text);
            }
        }
    return $return;
    }
};