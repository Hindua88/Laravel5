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
            $this->world->writeInfoLog("Animal {$this->name} ate {$data->name}!");
            $this->world->moveAnimal($this, $this->newX, $this->newY);
            return true;
        }

        return false;
    }

    public function born()
    {
        $egg = new Egg($this->type);
        $this->world->writeInfoLog("Animal {$this->name} was born {$egg->name} with position ({$this->newX}, {$this->newY})");
        $this->world->addEgg($egg, $this->newX, $this->newY);
    }

    public function isDie()
    {
        if ($this->step >= 6) {
            $this->world->writeInfoLog("Animal {$this->name} was died");
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
        $positions = array();
        if ($x > 0) {
            $positions[] = array('x' => $x - 1, 'y' => $y);
        }
        if ($x < $this->world->n - 1) {
            $positions[] = array('x' => $x + 1, 'y' => $y);
        } 
        if ($y > 0) {
            $positions[] = array('x' => $x, 'y' => $y - 1);
        }
        if ($y < $this->world->m - 1) {
            $positions[] = array('x' => $x, 'y' => $y + 1);
        } 
        // filter
        foreach ($positions as $step) {
            $data = $this->world->getDataCell($step['x'], $step['y']);
            if ($data && $data->type == $this->type) { // the same type animal
                continue;
            }
            $result[] = $step;
        }
        
        return $result;
    }
}
