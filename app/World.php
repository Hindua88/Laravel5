<?php

namespace App;

class World 
{
    public $n;
    public $m; 
    public $data = array();
    public $animals = array();
    public $is_active;
    public $histories = array();

    public function __construct($n, $m)
    {
        $this->n = $n;
        $this->m = $m;

        $this->init();
    }

    private function init()
    {
        for ($i = 0; $i < $this->n; $i ++) {
            for ($j = 0; $j < $this->m; $j ++) {
                $key = $i . '_' . $j;
                $this->data[$key] = null;
            }
        }
    }

    public function getAllAnimals()
    {
        $animals = array();
        for ($i = 0; $i < $this->n; $i ++) {
            for ($j = 0; $j < $this->m; $j ++) {
                $obj = $this->getDataCell($i, $j);
                if ($obj) {
                    if ($obj instanceof Egg) {
                        // nothing
                    } else {
                        $animals[] = $obj;
                    }
                }
            }
        }

        return $animals;
    }

    public function start()
    {
        for ($i = 0; $i < 100; $i ++) {
            $this->histories[] = $this->data;
            $this->animals = $this->getAllAnimals();
            if (count($this->animals) <= 0) {
                break;
            }
            foreach ($this->animals as $animal) {
                $animal->action();
            }
        }
    }

    public function addEgg(Egg &$egg, $x, $y)
    {
        $position = $x . '_' . $y;
        if (array_key_exists($position, $this->data)) {
            $egg->world = $this;
            $egg->x = $x;
            $egg->y = $y;
            $this->data[$position] = $egg; 
        }
    }

    public function addAnimal(Animal &$animal, $x, $y)
    {
        $position = $x . '_' . $y;
        if (array_key_exists($position, $this->data)) {
            $animal->world = $this;
            $animal->x = $x;
            $animal->y = $y;
            $this->data[$position] = $animal; 
        }
    }

    public function removeAnimal(Animal $animal)
    {
        $position = $animal->x . '_' . $animal->y;
        if (array_key_exists($position, $this->data)) {
            $this->data[$position] = null;
        }
    }

    public function moveAnimal(Animal &$animal, $newX, $newY)
    {
        $position = $animal->x . '_' . $animal->y;
        $newPosition = $newX . '_' . $newY;
        if (array_key_exists($position, $this->data) && array_key_exists($newPosition, $this->data)) {
            $animal->x = $newX; 
            $animal->y = $newY;
            $this->data[$newPosition] = $this->data[$position]; 
            $this->data[$position] = null;
        }
    }

    public function getDataCellWithPosition($position)
    {
        if (array_key_exists($position, $this->data)) {
            return $this->data[$position];
        }

        return null;
    }

    public function getDataCell($x, $y)
    {
        $key = $x . '_' . $y;
        return $this->getDataCellWithPosition($key);
    }

    /*
     * Get random empty position
     *
     *
     * @return array(x, y)
     */
    public function randomEmptyPosition()
    {
        $result = null;
        $indexes = array_keys($this->data);
        $max_step = $this->n * $this->m;
        for ($i = 0; $i < $max_step; $i ++) {
            $key = Common::getRandomInArray($indexes);
            $value = $this->data[$key];
            if (empty($value)) {
                $coordinate = explode('_', $key);
                $result = array('x' => $coordinate[0], 'y' => $coordinate[1]);
                break;
            }
        }

        return $result; 
    }

}
