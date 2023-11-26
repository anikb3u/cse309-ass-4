<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="homestyle.css">
  <title>Weather today</title>
</head>
<body>
  
  <div class="weather">
    <form method="GET">
      <input type="search" name="city" class="wsearch" placeholder="your city" value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>">
      <input type="submit" value="Search">
    </form>

    <?php
    if (isset($_GET['city']) && !empty($_GET['city'])) {
      $city = urlencode($_GET['city']);
      $url_current = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid=ce5ee56c1bdd4bb0d266602076d6c26b&units=metric";
      $url_forecast = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid=ce5ee56c1bdd4bb0d266602076d6c26b&units=metric";
    
      $response_current = file_get_contents($url_current);
      $clima_current = json_decode($response_current);
    
      $response_forecast = file_get_contents($url_forecast);
      $clima_forecast = json_decode($response_forecast);
    
      if ($clima_current->cod == 200 && $clima_forecast->cod == 200) {
        // Current weather data
        $weather_current = $clima_current->weather[0];
        $icon_current = "images/{$weather_current->icon}.png";
    
        $cityName = $clima_current->name;
        $day_current = date('l', $clima_current->dt);
        $humidity_current = round($clima_current->main->humidity);
        $wind_current = round($clima_current->wind->speed);
        $pressure_current = round($clima_current->main->pressure);
        $temp_current = round($clima_current->main->temp);
      
    
         // Forecast data
  $forecasts = [];

  foreach ($clima_forecast->list as $forecast) {
    $date = date('Y-m-d', $forecast->dt);
    if (!isset($forecasts[$date])) {
      $forecasts[$date] = $forecast;
    }
  }

  // Output the HTML
  ?>
  <div class="today">
    <div class="details">
      <h1 class="city"><?php echo ($cityName); ?></h1>
      <!--<p class="day"><?php echo ($day_current); ?></p>-->
      <div class="extradetails">
        <p class="humidity"><span class="value1"><?php echo ($humidity_current); ?></span> % Humidity</p>
        <p class="wind"><span class="value2"><?php echo ($wind_current); ?></span> m/s Wind</p>
        <p class="pressure"><span class="value3"><?php  echo ($pressure_current); ?></span> hPa Pressure</p>
      </div>
    </div>
    <img src="<?php echo ($icon_current); ?>" alt="Weather icon" class="wimage">
    <div class="wtemp"><span class="value4"><?php echo ($temp_current); ?></span>&deg;C</div>
  </div>
  <div class="forecast">
    <?php
    $i = 0;
    foreach ($forecasts as $date => $forecast) {
      if ($i++ >= 5) {
        break;
      }
      $icon = "images/{$forecast->weather[0]->icon}.png";
      $day = date('l', $forecast->dt);
      $temp = round($forecast->main->temp);
      ?>
           <article>
              <img src="<?php echo($icon); ?>" alt="Weather icon" class="forecasticon">
              <h3 class="day1"><?php echo ($day); ?></h3>
              <p class="temp1"><span class="value"><?php echo ($temp); ?></span>&deg;C</p>
            </article>
            <?php
              }
            ?>
          </div>
    <?php
        } 
        else {
          echo "<p>Error: Invalid city name or API request limit exceeded.</p>";
        }
      }
    ?>
  </div>
</body>
</html>