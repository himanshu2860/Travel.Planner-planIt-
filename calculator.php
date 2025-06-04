<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

$page_title = "Budget Calculator";

// Initialize variables
$destination_id = isset($_GET['destination']) ? $_GET['destination'] : null;
$selected_destination = null;

// If destination is specified, get details
if ($destination_id) {
    $selected_destination = getDestination($conn, $destination_id);
}

// Get all destinations for dropdown
$destinations = getAllDestinations($conn);

// Set page-specific JS
$page_specific_js = ['js/calculator.js'];

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Budget Calculator</h1>
            <p class="lead">Estimate the cost of your trip to Himachal Pradesh</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="calculator-container">
                <form id="calculatorForm">
                    <div class="mb-4">
                        <h4>1. Choose Destination</h4>
                        <select class="form-select" id="destination" name="destination">
                            <option value="">Select a destination</option>
                            <?php foreach ($destinations as $destination): ?>
                                <option value="<?php echo $destination['id']; ?>" <?php echo ($destination_id == $destination['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($destination['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <h4>2. Trip Details</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adults" class="form-label">Number of Adults</label>
                                <input type="number" class="form-control" id="adults" name="adults" min="1" value="2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="children" class="form-label">Number of Children</label>
                                <input type="number" class="form-control" id="children" name="children" min="0" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4>3. Accommodation</h4>
                        <div class="mb-3">
                            <label class="form-label">Accommodation Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accommodationType" id="budget" value="budget" checked>
                                <label class="form-check-label" for="budget">
                                    Budget (₹1,000 - ₹2,500 per night)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accommodationType" id="midRange" value="midRange">
                                <label class="form-check-label" for="midRange">
                                    Mid-Range (₹2,500 - ₹5,000 per night)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accommodationType" id="luxury" value="luxury">
                                <label class="form-check-label" for="luxury">
                                    Luxury (₹5,000+ per night)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4>4. Transportation</h4>
                        <div class="mb-3">
                            <label class="form-label">Transportation Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transportationType" id="public" value="public" checked>
                                <label class="form-check-label" for="public">
                                    Public Transport (Bus, Shared Taxi)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transportationType" id="privateTaxi" value="privateTaxi">
                                <label class="form-check-label" for="privateTaxi">
                                    Private Taxi
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transportationType" id="selfDriving" value="selfDriving">
                                <label class="form-check-label" for="selfDriving">
                                    Self-Driving/Rental Car
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4>5. Activities & Extras</h4>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activities" id="trekking" value="trekking">
                                <label class="form-check-label" for="trekking">
                                    Trekking/Hiking (₹1,000 - ₹3,000 per person)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activities" id="paragliding" value="paragliding">
                                <label class="form-check-label" for="paragliding">
                                    Paragliding (₹2,000 - ₹5,000 per person)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activities" id="riverRafting" value="riverRafting">
                                <label class="form-check-label" for="riverRafting">
                                    River Rafting (₹1,000 - ₹2,000 per person)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activities" id="camping" value="camping">
                                <label class="form-check-label" for="camping">
                                    Camping (₹1,500 - ₹3,000 per person)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4>6. Food Preferences</h4>
                        <div class="mb-3">
                            <label class="form-label">Food Budget</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="foodBudget" id="foodBudget" value="budget" checked>
                                <label class="form-check-label" for="foodBudget">
                                    Budget (₹500 - ₹800 per day per person)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="foodBudget" id="foodMidRange" value="midRange">
                                <label class="form-check-label" for="foodMidRange">
                                    Mid-Range (₹800 - ₹1,500 per day per person)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="foodBudget" id="foodLuxury" value="luxury">
                                <label class="form-check-label" for="foodLuxury">
                                    Luxury (₹1,500+ per day per person)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="calculateBtn" class="btn btn-primary">Calculate Budget</button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="calculator-result" id="resultContainer" style="display: none;">
                <h3 class="mb-3">Estimated Budget</h3>
                
                <div class="total-cost mb-4">
                    <h2 class="text-primary" id="totalCost">₹0</h2>
                    <p class="text-muted" id="tripDuration">0 days, 0 nights</p>
                </div>
                
                <div class="cost-breakdown">
                    <h5>Cost Breakdown</h5>
                    
                    <div class="expense-item">
                        <span>Accommodation</span>
                        <span id="accommodationCost">₹0</span>
                    </div>
                    
                    <div class="expense-item">
                        <span>Transportation</span>
                        <span id="transportationCost">₹0</span>
                    </div>
                    
                    <div class="expense-item">
                        <span>Food & Drinks</span>
                        <span id="foodCost">₹0</span>
                    </div>
                    
                    <div class="expense-item">
                        <span>Activities</span>
                        <span id="activitiesCost">₹0</span>
                    </div>
                    
                    <div class="expense-item">
                        <span>Miscellaneous</span>
                        <span id="miscCost">₹0</span>
                    </div>
                </div>
                
                <?php if (isLoggedIn()): ?>
                    <div class="mt-4">
                        <a href="itinerary.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-calendar-alt me-2"></i> Create Itinerary
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mt-4">
                        <div class="alert alert-info mb-2">
                            <small><i class="fas fa-info-circle me-1"></i> Log in to save this budget with your itinerary</small>
                        </div>
                        <a href="login.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Save
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <button class="btn btn-outline-secondary w-100" id="printBtn">
                        <i class="fas fa-print me-2"></i> Print Estimate
                    </button>
                </div>
            </div>
            
            <?php if ($selected_destination): ?>
            <div class="card mt-4">
                <img src="<?php echo htmlspecialchars($selected_destination['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($selected_destination['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($selected_destination['name']); ?></h5>
                    <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($selected_destination['location']); ?></small></p>
                    <p class="card-text">Price Range: <?php echo htmlspecialchars($selected_destination['price_range']); ?></p>
                    <p class="card-text">Ideal Duration: <?php echo htmlspecialchars($selected_destination['duration']); ?></p>
                    <a href="destination.php?id=<?php echo $selected_destination['id']; ?>" class="btn btn-outline-primary">View Details</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>