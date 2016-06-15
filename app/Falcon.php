<?php

namespace App;

class Falcon extends Animal implements IAnimal
{

    public function __construct()
    {
        $this->name = 'Falcon';
        $this->image = '/imgs/falcon.png';
        $this->type = Common::TYPE_FALCON;
    }

    public function isDie()
    {
        if ($this->step >= 4) {
            $this->setIsDie(true);
        }

        return $this->is_die;
    }

    public function action()
    {
        if ($this->isDie()) {
            $this->world->writeInfoLog("Animal {$this->name} ({$this->x}, {$this->y}) was died");
            return;
        }
        $this->world->writeInfoLog("Animal {$this->name} ({$this->x}, {$this->y}) action with number step: {$this->step}!");
        $next_steps = $this->getNextSteps();
        if (empty($next_steps)) { // idle
            $this->step ++;
            return;
        }
        $next_step = Common::getRandomInArray($next_steps);
        $this->newX = $next_step['x'];
        $this->newY = $next_step['y'];
        // eat
        if ($this->eat()) {
            $this->step = 0;
            return;
        } else {
            $this->step ++;
        }
        // born
        if ($this->step == 3) {
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

    public function getNextSteps()
    {
        $result = array();
        $x = $this->x;
        $y = $this->y;

        // detect egg or chicken to eat
        $animals = $this->world->getAllAnimals();
        foreach ($animals as $animal) {
            if ($animal instanceof Egg || $animal instanceof Chicken) {
                $result[] = array('x' => $animal->x, 'y' => $animal->y);
                // eat first
                return $result;
            }
        }

        // Not eat => get random position in map
        $coordinate = $this->world->randomEmptyPosition();
        if ($coordinate) {
            $result[] = array('x' => $coordinate['x'], 'y' => $coordinate['y']);
        }

        return $result;
    }
    
    public function getEmptyNextSteps()
    {
        $result = array();
        // any where empty
        $coordinate = $this->world->randomEmptyPosition();
        if ($coordinate) {
            $result[] = array('x' => $coordinate['x'], 'y' => $coordinate['y']);
        }

        return $result;
    }
}
