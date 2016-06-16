<?php

namespace App;

class Common {

    const TYPE_DINOSAUR = 1;
    const TYPE_FALCON = 2;
    const TYPE_CHICKEN = 3;
    const MAX_STEP = 200;

    public static function getRandomInArray($array = array())
    {
        $result = null;
        if (empty($array)) {
            return $result;
        }
        $index = array_rand($array);
        $result = $array[$index];

        return $result;
    }

}
