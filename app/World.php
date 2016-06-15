<?php

namespace App;

class World 
{
    public $n;
    public $m; 
    public $data = array();
    public $animals = array();
    public $histories = array();
    public $logger;

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

    public function setLogger($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        }
    }

    public function writeInfoLog($text)
    {
        if ($this->logger) {
            $this->logger->info($text);
        }
    }

    public function getAllAnimals()
    {
        $animals = array();
        for ($i = 0; $i < $this->n; $i ++) {
            for ($j = 0; $j < $this->m; $j ++) {
                $obj = $this->getDataCell($i, $j);
                if ($obj) {
//                    if ($obj instanceof Egg) {
//                        // nothing
//                    } else {
//                        $animals[] = $obj;
//                    }
                    $animals[] = $obj;
                }
            }
        }

        $result = array();
        $this->writeInfoLog("==> Total animal " . count($animals));
        if (count($animals) > 1) {
            // random indexes
            $indexes = array_rand($animals, count($animals) - 1);
            if (! is_array($indexes)) {
                $indexes = (array) $indexes;
            }
            foreach ($indexes as $index) {
                $item = $animals[$index];
                $result[] = $item;
                unset($animals[$index]);
            }
            foreach ($animals as $item) {
                $result[] = $item;
            }
        } else {
            $result = $animals;
        }

        return $result;
    }

    private function getNumberTypeAnimal($animals)
    {
        $result = array(); 
        foreach ($animals as $animal) {
            $type = $animal->type;
            if ($animal instanceof Egg) {
                continue;
            }
            if (! in_array($type, $result)) {
                $result[] = $type;
            }
        }

        return count($result);
    }

    public function start()
    {
        $this->writeInfoLog("START WORLD");
        for ($i = 0; $i < Common::MAX_STEP; $i ++) {
            $count = $i + 1;
            $this->writeInfoLog("======== STEP {$count} =========");
            $this->histories[] = $this->data;
            $this->animals = $this->getAllAnimals();
            $number_type = $this->getNumberTypeAnimal($this->animals);
            if ($number_type <= 1) { // only animal (except egg) => exit
                break;
            }
            foreach ($this->animals as $animal) {
                $animal->action();
            }
        }
        $this->writeInfoLog("END WORLD");
    }

    public function addEgg(Egg &$egg, $x, $y)
    {
        $position = $x . '_' . $y;
        if (array_key_exists($position, $this->data)) {
            $this->writeInfoLog("Add {$egg->name} to position ({$x}, {$y})");
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
            $this->writeInfoLog("Add {$animal->name} to position ({$x}, {$y})");
            $animal->world = $this;
            $animal->x = $x;
            $animal->y = $y;
            $this->data[$position] = $animal; 
        }
    }

    public function growAnimalFromEgg(Animal &$animal, $x, $y)
    {
        $position = $x . '_' . $y;
        if (array_key_exists($position, $this->data)) {
            $this->writeInfoLog("Grow Animal {$animal->name} from egg with position ({$x}, {$y})");
            $animal->world = $this;
            $animal->x = $x;
            $animal->y = $y;
            $this->data[$position] = $animal; 
        }
    }

    public function removeAnimal(Animal &$animal)
    {
        $position = $animal->x . '_' . $animal->y;
        if (array_key_exists($position, $this->data)) {
            $animal->setIsDie(true);
            $this->writeInfoLog("Remove {$animal->name} with position ({$animal->x}, {$animal->y})");
            $this->data[$position] = null;
        }
    }

    public function moveAnimal(Animal &$animal, $newX, $newY)
    {
        $position = $animal->x . '_' . $animal->y;
        $newPosition = $newX . '_' . $newY;
        if (array_key_exists($position, $this->data) && array_key_exists($newPosition, $this->data)) {
            $this->writeInfoLog("Move animal {$animal->name} from ({$animal->x}, {$animal->y}) to ({$newX}, {$newY})");
            $animal->x = $newX; 
            $animal->y = $newY;
            $this->data[$newPosition] = $this->data[$position]; 
            $this->data[$position] = null;
        } else {
            $this->writeInfoLog("Don't move");
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
