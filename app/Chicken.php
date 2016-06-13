<?php

namespace App;

class Chicken extends Animal
{

    public function __construct()
    {
        $this->name = 'Chicken';
        $this->image = '/imgs/chicken.png';
        $this->type = Common::TYPE_CHICKEN;
    }

    public function go()
    {
        $this->world->moveAnimal($this, $this->newX, $this->newY);
    }

    public function eat()
    {
        $data = $this->world->getDataCell($this->newX, $this->newY);
        if ($data instanceof Egg) {
            $this->world->moveAnimal($this, $this->newX, $this->newY);
            return true;
        }

        return false;
    }

    public function born()
    {
        $egg = new Egg($this->type);
        $this->world->addEgg($egg, $this->newX, $this->newY);
        $steps = $this->getMoveSteps();
        if (empty($steps)) {
            return;
        }
        $step = Common::getRandomInArray($steps);
        $egg = new Egg($this->type);
        $this->world->addEgg($egg, $step['x'], $step['y']);
    }

    public function isDie()
    {
        if ($this->step >= 3) {
            return true;
        }

        return false;
    }

    public function action()
    {
        $steps = $this->getMoveSteps();
        if (empty($steps)) {
            return;
        }
        $step = Common::getRandomInArray($steps);
        $this->newX = $step['x'];
        $this->newY = $step['y'];
        // eat
        if ($this->eat()) {
            $this->step = 0;
            return;
        } else {
            $this->step ++;
        }
        // born
        if ($this->step == 2) {
            $this->born();
            return;
        }
        // go 
        $this->go();
        // die => remove
        if ($this->isDie()) {
            $this->world->removeAnimal($this);
        }
    }

    public function getMoveSteps()
    {
        $result = array();
        $x = $this->x;
        $y = $this->y;

        // The x-axis 
        for ($i = 0; $i < $this->world->n; $i ++) {
            if ($x == $i) {
                continue;
            }
            $tmp = $this->world->getDataCell($i, $y);
            if (empty($tmp) || $tmp instanceof Egg) {
                $result[] = array('x' => $i, 'y' => $y);
            }
        }
        // The y-axis 
        for ($i = 0; $i < $this->world->m; $i ++) {
            if ($y == $i) {
                continue;
            }
            $tmp = $this->world->getDataCell($x, $i);
            if (empty($tmp) || $tmp instanceof Egg) {
                $result[] = array('x' => $x, 'y' => $i);
            }
        }
        
        return $result;
    }

}
