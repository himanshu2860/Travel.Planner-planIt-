<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

$page_title = "Destinations";

// Fetch all destinations
$sql = "SELECT * FROM destinations ORDER BY name";
$result = $conn->query($sql);
$destinations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="page-header py-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1 class="display-4">Explore Himachal Pradesh</h1>
                <p class="lead">Discover the most beautiful destinations in the Himalayan state</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="search" class="form-label">Search Destinations</label>
                                <input type="text" class="form-control" id="search" placeholder="Enter destination name...">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary w-100" id="searchBtn">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="destinationsList">
        <?php foreach ($destinations as $destination): ?>
        <div class="col-md-4 mb-4 destination-item">
            <div class="card h-100 destination-card">
               <?php
$imageSrc = $destination['image'];
if (!preg_match('/^https?:\/\//', $imageSrc)) {
    $imageSrc = './' . $imageSrc;
}
?>
<img src="<?php echo htmlspecialchars($imageSrc); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($destination['name']); ?>">

                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($destination['name']); ?></h5>
                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($destination['location']); ?></p>
                    <p class="card-text"><?php echo substr(htmlspecialchars($destination['description']), 0, 120); ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Best time: <?php echo htmlspecialchars($destination['best_time']); ?></small>
                        <a href="destination.php?id=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div id="noResults" class="text-center py-5" style="display: none;">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No destinations found matching your search.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('searchBtn');
        const destinationsList = document.getElementById('destinationsList');
        const noResults = document.getElementById('noResults');
        const destinations = document.querySelectorAll('.destination-item');
        
        function filterDestinations() {
            const searchTerm = searchInput.value.toLowerCase();
            let resultsFound = false;
            
            destinations.forEach(function(destination) {
                const title = destination.querySelector('.card-title').textContent.toLowerCase();
                const description = destination.querySelector('.card-text').textContent.toLowerCase();
                const location = destination.querySelector('.text-muted').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || location.includes(searchTerm)) {
                    destination.style.display = 'block';
                    resultsFound = true;
                } else {
                    destination.style.display = 'none';
                }
            });
            
            if (resultsFound) {
                noResults.style.display = 'none';
            } else {
                noResults.style.display = 'block';
            }
        }
        
        searchBtn.addEventListener('click', filterDestinations);
        
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                filterDestinations();
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>