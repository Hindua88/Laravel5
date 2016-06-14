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
        if ($this->step >= 4) {
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


    public function getMoveSteps()
    {
        $result = array();
        $x = $this->x;
        $y = $this->y;

        // detect egg or chicken 
        $animals = $this->world->getAllAnimals();
        foreach ($animals as $animal) {
            if ($animal instanceof Egg || $animal instanceof Chicken) {
                $result[] = array('x' => $animal->x, 'y' => $animal->y);
                break;
            }
        }

        // Not eat => get random position in map
        $coordinate = $this->world->randomEmptyPosition();
        if ($coordinate) {
            $result[] = array('x' => $coordinate['x'], 'y' => $coordinate['y']);
        }

        return $result;
    }

}
