<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    const ROUND_DECIMALS = 2;

    const MAX_CLOSEST_SECRETS = 3;

    public static $conversionRates = [
        'km'    => 1.853159616,
        'mile'  => 1.1515
    ];

    public static $cacheSecrets = [
        [
            'id' => 100,
            'name' => 'amber',
            'location' => [
                'latitude'  => 42.8805,
                'longitude' => -8.54569,
                'name'      => 'Santiago de Compostela'
            ]
        ],
        [
            'id' => 100,
            'name' => 'diamond',
            'location' => [
                'latitude'  => 38.2622,
                'longitude' => -0.70107,
                'name'      => 'Elche'
            ]
        ],
        [
            'id' => 100,
            'name' => 'pearl',
            'location' => [
                'latitude'  => 41.8919,
                'longitude' => 12.5113,
                'name'      => 'Rome'
            ]
        ],
        [
            'id' => 100,
            'name' => 'ruby',
            'location' => [
                'latitude'  => 53.4106,
                'longitude' => -2.9779,
                'name'      => 'Liverpool'
            ]
        ],
        [
            'id' => 100,
            'name' => 'sapphire',
            'location' => [
                'latitude'  => 50.08804,
                'longitude' => 14.42076,
                'name'      => 'Prague'
            ]
        ],
    ];

    protected function convertDistance($distance, $unit = 'km')
    {
        switch (strtolower($unit)) {
            case 'mile':
                $distance = $distance * self::$conversionRates['mile'];
                break;
            default :
                $distance = $distance * self::$conversionRates['km'];
                break;
        }

        return round($distance, self::ROUND_DECIMALS);
    }

    public function index(Request $request)
    {
        return response()->json(['method' => 'index']);
    }

    public function get($id)
    {
        return response()->json(['method' => 'get', 'id' => $id]);
    }

    public function create(Request $request)
    {
        return response()->json(['method' => 'create']);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['method' => 'update', 'id' => $id]);
    }

    public function delete($id)
    {
        return response()->json(['method' => 'delete', 'id' => $id]);
    }

    public function getClosestSecrets($originPoint)
    {
        $closestSecrets = [];

        $distances = array_map(function($item) use($originPoint) {
            return $this->getDistance($item['location'], $originPoint);
        }, self::$cacheSecrets);

        asort($distances);

        $distances = array_slice($distances, 0, self::MAX_CLOSEST_SECRETS, true);

        foreach ($distances as $key => $distance) {
            $closestSecrets[] = self::$cacheSecrets[$key];
        }

        return $closestSecrets;
    }

    public function getHaversineDistance($pointA, $pointB, $unit = 'km')
    {
        $distance = rad2deg(
            acos(
                (sin(deg2rad($pointA['latitude'])) * sin(deg2rad($pointB['latitude']))) +
                (cos(deg2rad($pointA['latitude'])) * cos(deg2rad($pointB['latitude'])) * cos(deg2rad($pointA['longitude'] - $pointB['longitude'])))
            )
        ) * 60;

        return $this->convertDistance($distance, $unit);
    }

    public function getEuclideanDistance($pointA, $pointB, $unit = 'km')
    {
        $distance = sqrt(
            pow(abs($pointA['latitude'] - $pointB['latitude']), 2) + pow(abs($pointA['longitude'] - $pointB['longitude']), 2)
        );

        return $this->convertDistance($distance, $unit);
    }

    public function getDistance($pointA, $pointB, $unit = 'km')
    {
        return $this->getHaversineDistance($pointA, $pointB, $unit);
        //return $this->getEuclideanDistance($pointA, $pointB, $unit);
    }
}
