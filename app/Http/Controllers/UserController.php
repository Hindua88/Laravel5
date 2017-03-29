<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\World;
use App\Animal;
use App\Dinosaur;
use App\Falcon;
use App\Chicken;
use App\Egg;
use App\Common;
use App\Logger;


class UserController extends Controller
{
    private $world;

    private function addAnmials()
    {

        // add dinosaur
        $dinosaur = new Dinosaur();
        $coordinate = $this->world->randomEmptyPosition();
        $this->world->addAnimal($dinosaur, $coordinate['x'], $coordinate['y']);

        // add falcon
        for ($i = 0; $i < 2; $i ++) {
            $coordinate = $this->world->randomEmptyPosition();
            $falcon = new Falcon();
            $this->world->addAnimal($falcon, $coordinate['x'], $coordinate['y']);
        }

        // add chicken
        for ($i = 0; $i < 4; $i ++) {
            $coordinate = $this->world->randomEmptyPosition();
            $chicken = new Chicken();
            $this->world->addAnimal($chicken, $coordinate['x'], $coordinate['y']);
        }
    }

    // TO DO: This is example and use public property and public method. Please use private or protected for both property and method to make good program.
    public function main()
    {
        $this->world = new World(16, 16);
        $logger = new Logger('/tmp/animal.log', Logger::DEBUG);
        $this->world->setLogger($logger);
        $this->world->writeInfoLog("INIT WORLD");
        $this->addAnmials(); // init
        $this->world->start(); // start

        return view('user.main', [
            'world' => $this->world,
            'cell_width' => 36,
            'cell_height' => 36,
            ]);
    }

    public function test() {
        var_dump(1);
    }
}
