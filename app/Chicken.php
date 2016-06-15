<?php

namespace App;

class Chicken extends Animal implements IAnimal
{

    public function __construct()
    {
        $this->name = 'Chicken';
        $this->image = '/imgs/chicken.png';
        $this->type = Common::TYPE_CHICKEN;
    }

    public function born()
    {
        for ($i = 0; $i < 2; $i ++) {
            parent::born();
        }
    }

    public function isDie()
    {
        if ($this->step >= 3) {
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

    public function getNextSteps()
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

    public function getEmptyNextSteps()
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
            if (empty($tmp)) {
                $result[] = array('x' => $i, 'y' => $y);
            }
        }
        // The y-axis 
        for ($i = 0; $i < $this->world->m; $i ++) {
            if ($y == $i) {
                continue;
            }
            $tmp = $this->world->getDataCell($x, $i);
            if (empty($tmp)) {
                $result[] = array('x' => $x, 'y' => $i);
            }
        }
        
        return $result;
    }
}
