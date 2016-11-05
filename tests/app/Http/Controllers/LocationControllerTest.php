<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class LocationControllerTest extends TestCase
{

    public function testDistance()
    {
        $realDistanceLondonAmsterdam = 358.06;

        $london = [
            'latitude'  => 51.50,
            'longitude' => -0.13
        ];

        $amsterdam = [
            'latitude'  => 52.37,
            'longitude' => 4.90
        ];

        $location = new App\Http\Controllers\LocationController();

        $calculatedDistance = $location->getDistance($london, $amsterdam);

        $this->assertClassHasStaticAttribute('conversionRates', App\Http\Controllers\LocationController::class);
        $this->assertEquals($realDistanceLondonAmsterdam, $calculatedDistance);
    }

    public function testClosestSecrets()
    {
        $currentLocation = [
            'latitude'  => 40.730610,
            'longitude' => -73.935242
        ];

        $location = new App\Http\Controllers\LocationController();

        $closestSecrets = $location->getClosestSecrets($currentLocation);

        $this->assertClassHasStaticAttribute('conversionRates', App\Http\Controllers\LocationController::class);
        $this->assertContainsOnly('array', $closestSecrets);
        $this->assertCount(3, $closestSecrets);

        // Checking the first element
        $currentElement = array_shift($closestSecrets);
        $this->assertArraySubset(['name' => 'amber'], $currentElement);

        // Second
        $currentElement = array_shift($closestSecrets);
        $this->assertArraySubset(['name' => 'ruby'], $currentElement);

        // Third
        $currentElement = array_shift($closestSecrets);
        $this->assertArraySubset(['name' => 'diamond'], $currentElement);
    }
}
