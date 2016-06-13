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
        $steps = $this->getMoveSteps();
        $step = Common::getRandomInArray($steps);
        $this->world->moveAnimal($this, $step['x'], $step['y']);
    }

    public function eat()
    {
        $steps = $this->getMoveSteps();
        $coordinate = array();
        foreach ($steps as $step) {
            $cell = $this->world->getDataCell($step['x'], $step['y']);
            if ($cell) {
                $coordinate = $cell;
                break;
            }
        }

        return $coordinate;
    }

    public function born()
    {
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
        // eat
        $coordinate = $this->eat();
        if ($coordinate) {
            $this->step = 0;
            $this->world->moveAnimal($this, $coordinate['x'], $coordinate['y']);
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
