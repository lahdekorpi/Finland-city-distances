# Finland-city-distances
Calculates distances between cities in Finland

## Usage
```php
$cd = new CityDistances();
echo $cd->cityNames; // Outputs list of cities in the database
echo $cd->cityDistance("Pori", "Helsinki"); // Outputs the distance between two cities in kilometers (374.92202880166)
echo $cd->distance(21.783, 61.483,  24.931,60.17); // Outputs the distance between two coordinates (lat, lng)
```
