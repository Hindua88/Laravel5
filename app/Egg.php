<?php

namespace App;

class Egg {

    public $x;
    public $y;
    public $name;
    public $image;
    public $type;
    public $world;
    protected $is_die = false;

    public function setIsDie($bool = true)
    {
        $this->is_die = $bool;
    }

    public function isDie()
    {
        return $this->is_die;
    }

    public function __construct($type)
    {
        $this->name = 'Egg';
        $this->image = '/imgs/egg.png';
        $this->type = $type;
        if ($this->type == Common::TYPE_DINOSAUR) {
            $this->name = 'Egg Dinosaur';
        } elseif ($this->type == Common::TYPE_FALCON) {
            $this->name = 'Egg Falcon';
        } elseif ($this->type == Common::TYPE_CHICKEN) {
            $this->name = 'Egg Chicken';
        }
    }

    public function action()
    {
        if ($this->isDie()) {
            $this->world->writeInfoLog("{$this->name} ({$this->x}, {$this->y}) was died");
            return;
        }
        // born animal
        if ($this->type == Common::TYPE_DINOSAUR) {
            $dinosaur = new Dinosaur();
            $this->world->growAnimalFromEgg($dinosaur, $this->x, $this->y);
        } elseif ($this->type == Common::TYPE_FALCON) {
            $falcon = new Falcon();
            $this->world->growAnimalFromEgg($falcon, $this->x, $this->y);
        } elseif ($this->type == Common::TYPE_CHICKEN) {
            $chicken = new Chicken(); 
            $this->world->growAnimalFromEgg($chicken, $this->x, $this->y);
        }
    }
} 
