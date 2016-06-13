<?php

namespace App;

class Chicken extends Animal
{

    public function __construct()
    {
        $this->name = 'Chicken';
        $this->image = '/imgs/chicken.png';
        $this->type = TYPE_CHICKEN;
    }

}
