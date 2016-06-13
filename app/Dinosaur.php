<?php

namespace App;

class Dinosaur extends Animal
{

    public function __construct()
    {
        $this->name = 'Dinosaur';
        $this->image = '/imgs/dinosaur.png';
        $this->type = Common::TYPE_DINOSAUR;
    }

    public function go()
    {
        $this->world->moveAnimal($this, $this->newX, $this->newY);
    }

    public function eat()
    {
        $data = $this->world->getDataCell($this->newX, $this->newY);
        if ($data) {
            $this->world->moveAnimal($this, $this->newX, $this->newY);
            return true;
        }

        return false;
    }

    public function born()
    {
        $egg = new Egg($this->type);
        $this->world->addEgg($egg, $this->newX, $this->newY);
    }

    public function isDie()
    {
        if ($this->step >= 6) {
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
        if ($this->step == 5) {
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
        if ($x > 0) {
            $result[] = array('x' => $x - 1, 'y' => $y);
        }
        if ($x < $this->world->n - 1) {
            $result[] = array('x' => $x + 1, 'y' => $y);
        } 
        if ($y > 0) {
            $result[] = array('x' => $x, 'y' => $y - 1);
        }
        if ($y < $this->world->m - 1) {
            $result[] = array('x' => $x, 'y' => $y + 1);
        } 
        
        return $result;
    }

}
