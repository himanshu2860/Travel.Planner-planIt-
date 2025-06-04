<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';
$page_title = "Home";
include 'includes/header.php';

// Fetch featured destinations
$sql = "SELECT * FROM destinations ORDER BY id LIMIT 6";
$result = $conn->query($sql);
$destinations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }
}

// Get weather for Shimla (default location)
$weather_data = getWeatherData('Shimla');
?>

<!-- Hero Section with Parallax -->
<div class="hero-section">
    <div class="parallax-container">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1 class="animate__animated animate__fadeInDown">Discover Himachal Pradesh</h1>
            <p class="animate__animated animate__fadeInUp">Plan your perfect mountain getaway</p>
            <div class="hero-buttons animate__animated animate__fadeInUp">
                <a href="destinations.php" class="btn btn-primary">Explore Destinations</a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-outline-light">Sign Up Now</a>
                <?php else: ?>
                    <a href="itinerary.php" class="btn btn-outline-light">Plan Your Trip</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Featured Destinations -->
<section class="container mt-5 mb-5">
    <div class="section-header text-center mb-5">
        <h2>Explore Top Destinations</h2>
        <p class="text-muted">Discover the beauty of Himachal Pradesh</p>
    </div>
    
    <div class="row">
        <?php foreach ($destinations as $destination): ?>
        <div class="col-md-4 mb-4">
            <div class="card destination-card h-100">
                <img src="<?php echo htmlspecialchars($destination['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($destination['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($destination['name']); ?></h5>
                    <p class="card-text"><?php echo substr(htmlspecialchars($destination['description']), 0, 100); ?>...</p>
                    <a href="destination.php?id=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-primary">Learn More</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="destinations.php" class="btn btn-primary">View All Destinations</a>
    </div>
</section>

<!-- Quick Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2>Plan Your Perfect Trip</h2>
            <p class="text-muted">Everything you need to make your Himachal adventure unforgettable</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-box text-center p-4">
                    <i class="fas fa-map-marked-alt fa-3x mb-3 text-primary"></i>
                    <h4>Itinerary Builder</h4>
                    <p>Create custom travel plans with our interactive itinerary builder</p>
                    <a href="itinerary.php" class="btn btn-sm btn-outline-primary mt-2">Build Itinerary</a>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="feature-box text-center p-4">
                    <i class="fas fa-calculator fa-3x mb-3 text-primary"></i>
                    <h4>Budget Calculator</h4>
                    <p>Estimate costs for your trip with our handy budget calculator</p>
                    <a href="calculator.php" class="btn btn-sm btn-outline-primary mt-2">Calculate Budget</a>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="feature-box text-center p-4">
                    <i class="fas fa-cloud-sun fa-3x mb-3 text-primary"></i>
                    <h4>Weather Forecast</h4>
                    <p>Check real-time weather forecasts for your destination</p>
                    <a href="weather.php" class="btn btn-sm btn-outline-primary mt-2">View Weather</a>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Call to Action -->
<section class="cta-section text-white text-center">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h2 class="mb-4">Ready for your Himalayan adventure?</h2>
            </div>
            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="register.php" class="btn btn-primary btn-lg">Sign Up & Start Planning</a>
                        <?php else: ?>
                            <a href="itinerary.php" class="btn btn-primary btn-lg">Create Your Itinerary Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function createSnowflake() {
        const snowflake = document.createElement('div');
        snowflake.classList.add('snowflake');
        snowflake.textContent = '❄';

        snowflake.style.left = Math.random() * window.innerWidth + 'px';
        snowflake.style.animationDuration = (Math.random() * 3 + 2) + 's';
        snowflake.style.opacity = Math.random();
        snowflake.style.fontSize = Math.random() * 10 + 10 + 'px';

        document.body.appendChild(snowflake);

        setTimeout(() => {
            snowflake.remove();
        }, 5000);
    }

    setInterval(createSnowflake, 200);
</script>
<?php include 'includes/footer.php'; ?>