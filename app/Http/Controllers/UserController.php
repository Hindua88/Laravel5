<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\World;
use App\Animal;
use App\Dinosaur;
use App\Falcon;
use App\Chicken;

class UserController extends Controller
{
    private $world;

    private function addAnmials()
    {
        // add dinosaur
        $dinosaur = new Dinosaur();
        $coordinate = $this->world->randomEmptyPosition();
        $this->world->addAnimal($dinosaur, $coordinate['x'], $coordinate['y']);
        /*
        // add falcon
        for ($i = 0; $i < 2; $i ++) {
            $coordinate = $this->world->randomEmptyPosition();
            $falcon = new Falcon();
            $this->world->addAnimal($falcon, $coordinate['x'], $coordinate['y']);
        }
        // add chicken
        for ($i = 0; $i < 3; $i ++) {
            $coordinate = $this->world->randomEmptyPosition();
            $chicken = new Chicken();
            $this->world->addAnimal($chicken, $coordinate['x'], $coordinate['y']);
        }
         */

        // start 
        $this->world->start();
    }

    public function main()
    {
//        $this->world = new World(16, 16);
        $this->world = new World(8, 8);
        $this->addAnmials(); // init

        return view('user.main', [
            'world' => $this->world,
            'cell_width' => 24,
            'cell_height' => 24,
            ]);
    }
}
