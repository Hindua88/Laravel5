<?php

namespace App;

class Egg {

    public $x;
    public $y;
    public $name;
    public $image;
    public $type;
    public $world;

    public function __construct($type)
    {
        $this->name = 'Egg';
        $this->image = '/imgs/egg.png';
        $this->type = $type;
    }

} 
