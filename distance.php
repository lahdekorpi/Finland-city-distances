<?php
/**
 * Public domain
 *
 * Usage:
 * $cd = new CityDistances();
 * echo $cd->cityNames; // Outputs list of cities in the database
 * echo $cd->cityDistance("Pori", "Helsinki"); // Outputs the distance between two cities in kilometers (374.92202880166)
 * echo $cd->distance(21.783, 61.483,  24.931,60.17); // Outputs the distance between two coordinates (lat, lng)
 * echo $cd->getCityCoordinates("Pori"); // Outputs the coordinates of a city array(2) { [0]=> float(21.783) [1]=> float(61.483) }
 */

class CityDistances {
    
    public $cities;
    public $cityNames;
    
    public function __construct($citiesFile="cities.tsv", $cacheFile="cities.serialized") {
        
        if(!file_exists($cacheFile)) {
            $cities=array();
            $cityNames=array();
            foreach(explode("\n",file_get_contents($citiesFile)) as $line) {
                $data=explode("\t",$line);
                if(!empty($data[0])) {
                    $cityNames[]=$data[0];
                    $cities[mb_strtolower($data[0],'UTF-8')]=array((float)$data[1],(float)$data[2]);
                }
            }
            file_put_contents($cacheFile,serialize(array($cities,$cityNames)));
        } else {
            $cache=unserialize(file_get_contents($cacheFile));
            $cities=$cache[0];
            $cityNames=$cache[1];
        }
        $this->cities=$cities;
        $this->cityNames=$cityNames;
    }

    public function distance($lat1, $lng1, $lat2, $lng2) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $radius = 6372.797; // Mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlng/2) * sin($dlng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $radius * $c;

        return $km;
    }
    
    public function cityDistance($city1, $city2) {
        $city1=mb_strtolower($city1);
        $city2=mb_strtolower($city2);
        if(isset($this->cities[$city1]) && isset($this->cities[$city2])) {
            return $this->distance($this->cities[$city1][0],$this->cities[$city1][1],$this->cities[$city2][0],$this->cities[$city2][1]);
        } else {
            return false;
        }
    }
    
    public function getCityCoordinates($city) {
        $city=mb_strtolower($city);
        if(isset($this->cities[$city]))
            return $this->cities[$city];
        else
            return false;
    }
}