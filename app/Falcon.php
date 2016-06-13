<?php

namespace App;

class Falcon extends Animal
{

    public function __construct()
    {
        $this->name = 'Falcon';
        $this->image = '/imgs/falcon.png';
        $this->type = Common::TYPE_FALCON;
    }

}
