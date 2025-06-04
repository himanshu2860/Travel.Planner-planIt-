<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Check if destination ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('destinations.php');
}

$destination_id = $_GET['id'];

// Fetch destination details
$destination = getDestination($conn, $destination_id);

if (!$destination) {
    redirect('destinations.php');
}

$page_title = $destination['name'];

// Get weather data for the destination
$weather_data = getWeatherData($destination['name']);

// Set page-specific JS
$page_specific_js = ['js/weather.js'];

include 'includes/header.php';
?>

<div class="destination-header" style="background-image: url('<?php echo htmlspecialchars($destination['image']); ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 text-center text-white">
                <h1 class="display-4"><?php echo htmlspecialchars($destination['name']); ?></h1>
                <p class="lead"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($destination['location']); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="mb-3">About <?php echo htmlspecialchars($destination['name']); ?></h2>
                    <p class="lead"><?php echo htmlspecialchars($destination['description']); ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Best Time to Visit</small>
                                    <span><?php echo htmlspecialchars($destination['best_time']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="fas fa-clock fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Ideal Duration</small>
                                    <span><?php echo htmlspecialchars($destination['duration']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="fas fa-rupee-sign fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Price Range</small>
                                    <span><?php echo htmlspecialchars($destination['price_range']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Location</h3>
                    <div class="map-container">
                        <iframe 
                            width="100%" 
                            height="350" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://maps.google.com/maps?q=<?php echo $destination['latitude']; ?>,<?php echo $destination['longitude']; ?>&hl=en&z=12&output=embed">
                        </iframe>
                    </div>
                </div>
            </div>
            
            <!-- Things to Do Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Things to Do</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="activity-item d-flex align-items-start">
                                <div class="icon-box me-3">
                                    <i class="fas fa-hiking fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5>Explore Local Attractions</h5>
                                    <p>Discover the beauty of local landmarks and scenic spots.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="activity-item d-flex align-items-start">
                                <div class="icon-box me-3">
                                    <i class="fas fa-utensils fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5>Try Local Cuisine</h5>
                                    <p>Taste the authentic Himachali food and beverages.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="activity-item d-flex align-items-start">
                                <div class="icon-box me-3">
                                    <i class="fas fa-camera fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5>Photography</h5>
                                    <p>Capture the breathtaking views of mountains and valleys.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="activity-item d-flex align-items-start">
                                <div class="icon-box me-3">
                                    <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5>Shop Local Handicrafts</h5>
                                    <p>Purchase authentic Himachali souvenirs and handicrafts.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Weather Widget -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h3 class="mb-3">Current Weather</h3>
                    <div class="weather-info" data-city="<?php echo htmlspecialchars($destination['name']); ?>">
                        <?php if ($weather_data): ?>
                            <div class="weather-icon mb-2">
                                <img src="http://openweathermap.org/img/wn/<?php echo $weather_data['icon']; ?>@2x.png" alt="Weather icon">
                            </div>
                            <div class="weather-temp">
                                <h2><?php echo round($weather_data['temp']); ?>°C</h2>
                                <p><?php echo $weather_data['description']; ?></p>
                                <div class="weather-details mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><i class="fas fa-temperature-high me-2"></i> Feels like: <?php echo round($weather_data['feels_like']); ?>°C</p>
                                        </div>
                                        <div class="col-6">
                                            <p><i class="fas fa-tint me-2"></i> Humidity: <?php echo $weather_data['humidity']; ?>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>Weather data temporarily unavailable.</p>
                        <?php endif; ?>
                    </div>
                    <a href="weather.php?city=<?php echo urlencode($destination['name']); ?>" class="btn btn-outline-primary mt-3">5-Day Forecast</a>
                </div>
            </div>
            
            <!-- Add to Itinerary -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Plan Your Visit</h3>
                    <?php if (isLoggedIn()): ?>
                        <a href="itinerary.php?add=<?php echo $destination_id; ?>" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Add to Itinerary
                        </a>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <p><i class="fas fa-info-circle me-2"></i> Please <a href="login.php">login</a> to add this destination to your itinerary.</p>
                        </div>
                    <?php endif; ?>
                    <a href="calculator.php?destination=<?php echo $destination_id; ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-calculator me-2"></i> Estimate Budget
                    </a>
                </div>
            </div>
            
            <!-- Nearby Destinations -->
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Nearby Destinations</h3>
                    <div class="nearby-destinations">
                        <?php
                        // Get random 3 destinations excluding current one
                        $sql = "SELECT id, name, image FROM destinations WHERE id != ? ORDER BY RAND() LIMIT 3";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $destination_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0):
                            while ($nearby = $result->fetch_assoc()):
                        ?>
                            <div class="nearby-item d-flex align-items-center mb-3">
                                <img src="<?php echo htmlspecialchars($nearby['image']); ?>" alt="<?php echo htmlspecialchars($nearby['name']); ?>" class="nearby-img me-3">
                                <div>
                                    <h5 class="mb-0"><?php echo htmlspecialchars($nearby['name']); ?></h5>
                                    <a href="destination.php?id=<?php echo $nearby['id']; ?>" class="btn btn-sm btn-link p-0">View Details</a>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <p>No nearby destinations found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .destination-header {
        height: 400px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .destination-header .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .nearby-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .icon-box {
        width: 50px;
        text-align: center;
    }
    
    .activity-item {
        margin-bottom: 1.5rem;
    }
    
    .map-container {
        border-radius: 4px;
        overflow: hidden;
    }
</style>

<?php include 'includes/footer.php'; ?>