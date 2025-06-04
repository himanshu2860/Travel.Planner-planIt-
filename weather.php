
<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

$page_title = "Weather Forecast";

// Get city from query parameter or default to Shimla
$city = isset($_GET['city']) ? $_GET['city'] : 'Shimla';

// Get current weather data
$current_weather = getWeatherData($city);
$forecast_data = getWeatherForecast($city);

// Destinations for selection
$destinations = getAllDestinations($conn);

// Set page-specific JS
$page_specific_js = ['js/weather.js'];

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Weather Forecast</h1>
            <p class="lead">Check the current weather and forecast for destinations in Himachal Pradesh</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form id="weatherForm" class="row g-3">
                        <div class="col-md-9">
                            <label for="citySelect" class="form-label">Select Destination</label>
                            <select class="form-select" id="citySelect" name="city">
                                <?php foreach ($destinations as $destination): ?>
                                    <option value="<?php echo htmlspecialchars($destination['name']); ?>" <?php echo ($city == $destination['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($destination['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Check</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="current-weather text-center">
                        <h3 class="mb-3">Current Weather in <?php echo htmlspecialchars($city); ?></h3>

                        <?php if ($current_weather): ?>
                            <div class="current-weather-details">
                                <div class="weather-icon mb-3">
                                    <img src="http://openweathermap.org/img/wn/<?php echo $current_weather['icon']; ?>@4x.png" alt="Weather icon">
                                </div>
                                <div class="temperature-display">
                                    <h1 class="display-1"><?php echo round($current_weather['temp']); ?>°C</h1>
                                    <p class="lead"><?php echo $current_weather['description']; ?></p>
                                </div>

                                <div class="weather-details mt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="detail-item">
                                                <i class="fas fa-temperature-high fa-fw"></i>
                                                <span>Feels like: <?php echo round($current_weather['feels_like']); ?>°C</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="detail-item">
                                                <i class="fas fa-tint fa-fw"></i>
                                                <span>Humidity: <?php echo $current_weather['humidity']; ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <div class="detail-item">
                                                <i class="fas fa-temperature-low fa-fw"></i>
                                                <span>Min: <?php echo round($current_weather['temp_min']); ?>°C</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="detail-item">
                                                <i class="fas fa-temperature-high fa-fw"></i>
                                                <span>Max: <?php echo round($current_weather['temp_max']); ?>°C</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Weather data is temporarily unavailable for <?php echo htmlspecialchars($city); ?>. Please try again later.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

      <div class="col-lg-6 d-flex">
    <div class="card mb-4 w-100 d-flex flex-column justify-content-center">
        <div class="card-body">

                   <h3 class="mb-3 text-center">Weather Forecast</h3>

                    <?php if ($forecast_data): ?>
                       <div class="forecast-container justify-content-center">

                            <?php foreach ($forecast_data as $day): ?>
                                <div class="forecast-day">
                                    <div class="day-header"><?php echo date('l', strtotime($day['date'])); ?></div>
                                    <div class="forecast-icon">
                                        <img src="http://openweathermap.org/img/wn/<?php echo $day['icon']; ?>@2x.png" alt="Icon">
                                    </div>
                                    <div class="forecast-temp"><?php echo $day['temp']; ?>°C</div>
                                    <div class="forecast-desc"><?php echo ucfirst($day['description']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> Forecast data not available for <?php echo htmlspecialchars($city); ?>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Travel Tips Section Fixed Here -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="mb-3">Travel Weather Tips</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="weather-tip">
                                <h5><i class="fas fa-snowflake text-primary me-2"></i> Winter (October to February)</h5>
                                <p>Temperatures can drop below freezing in many areas. Pack heavy winter clothing, thermal wear, gloves, caps, and waterproof shoes. Snowfall is common in higher elevations.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="weather-tip">
                                <h5><i class="fas fa-sun text-primary me-2"></i> Summer (March to June)</h5>
                                <p>Pleasant temperatures with cool mornings and evenings. Pack light woolens for evenings, comfortable walking shoes, sunscreen, sunglasses, and a hat for protection from the sun.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="weather-tip">
                                <h5><i class="fas fa-cloud-rain text-primary me-2"></i> Monsoon (July to September)</h5>
                                <p>Heavy rainfall and occasional landslides. Pack rain gear, waterproof bags, quick-dry clothing, and proper footwear. Check road conditions before traveling.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="weather-tip">
                                <h5><i class="fas fa-leaf text-primary me-2"></i> Autumn (September to November)</h5>
                                <p>Mild temperatures with clear skies. Perfect time for photography and outdoor activities. Pack a mix of light and medium-weight clothing for varying temperatures.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .current-weather-details {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .weather-icon img {
        width: 100px;
        height: 100px;
    }
    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
   .forecast-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}


.forecast-day {
    flex: 1 1 150px;
    max-width: 150px;
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.03);
}



    .day-header {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .forecast-icon img {
        width: 50px;
        height: 50px;
    }
    .forecast-temp {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 5px 0;
    }
    .forecast-desc {
        font-size: 0.9rem;
        color: var(--gray-600);
    }
    .weather-tip {
        margin-bottom: 20px;
    }
    @media (max-width: 767.98px) {
        .forecast-container {
            justify-content: flex-start;
        }
        .forecast-day {
            min-width: 100px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('weatherForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const city = document.getElementById('citySelect').value;
            window.location.href = 'weather.php?city=' + encodeURIComponent(city);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
