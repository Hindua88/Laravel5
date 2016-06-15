<?php

namespace App;

class Dinosaur extends Animal implements IAnimal
{

    public function __construct()
    {
        $this->name = 'Dinosaur';
        $this->image = '/imgs/dinosaur.png';
        $this->type = Common::TYPE_DINOSAUR;
    }

    public function isDie()
    {
        if ($this->step >= 6) {
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

    public function getNextSteps()
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
        foreach ($positions as $next_step) {
            $data = $this->world->getDataCell($next_step['x'], $next_step['y']);
            if ($data) {
                if ($data->type == $this->type) { // the same type animal
                    continue;
                }
                // first eat
                $result[] = $next_step;
                return $result;
            }
            $result[] = $next_step;
        }
        
        return $result;
    }

    public function getEmptyNextSteps() {
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
        foreach ($positions as $next_step) {
            $data = $this->world->getDataCell($next_step['x'], $next_step['y']);
            if (empty($data)) {
                $result[] = $next_step;
            }
        }
        
        return $result;
    }
}
