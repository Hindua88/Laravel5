<?php

namespace App;

class Animal 
{
    public $x;
    public $y;
    public $name;
    public $image;
    public $type;
    public $world;
    public $step = 0;
    public $newX;
    public $newY;
    protected $is_die = false;

    public function setIsDie($bool = true)
    {
        $this->is_die = $bool;
    }

    public function go()
    {
        $this->world->moveAnimal($this, $this->newX, $this->newY);
    }

    public function eat()
    {
        $obj = $this->world->getDataCell($this->newX, $this->newY);
        if ($obj) {
            $obj->setIsDie(true);
            $this->world->writeInfoLog("Animal {$this->name} ({$this->x}, {$this->y}) ate {$obj->name} ({$obj->x}, {$obj->y})!");
            $this->world->moveAnimal($this, $this->newX, $this->newY);
            return true;
        }

        return false;
    }

    public function born()
    {
        $next_steps = $this->getEmptyNextSteps();
        if (empty($next_steps)) {
            return;
        }
        $next_step = Common::getRandomInArray($next_steps);
        if ($next_step) {
            $newX = $next_step['x'];
            $newY = $next_step['y'];
            $egg = new Egg($this->type);
            $this->world->writeInfoLog("Animal {$this->name} was born {$egg->name} with position ({$newX}, {$newY})");
            $this->world->addEgg($egg, $newX, $newY);
        }
    }
}
