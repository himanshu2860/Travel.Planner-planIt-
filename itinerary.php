<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$page_title = "Itinerary Builder";

// Process adding a destination to a trip
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $destination_id = $_GET['add'];
    $destination = getDestination($conn, $destination_id);
    
    if ($destination) {
        // Store in session for later use
        if (!isset($_SESSION['selected_destinations'])) {
            $_SESSION['selected_destinations'] = [];
        }
        
        // Check if destination already exists in session
        $exists = false;
        foreach ($_SESSION['selected_destinations'] as $selected) {
            if ($selected['id'] == $destination_id) {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            $_SESSION['selected_destinations'][] = $destination;
            showAlert('Destination added to your itinerary.', 'success');
        } else {
            showAlert('This destination is already in your itinerary.', 'info');
        }
    }
    
    // Redirect to remove query parameters
    redirect('itinerary.php');
}

// Remove destination from session
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    
    if (isset($_SESSION['selected_destinations'])) {
        foreach ($_SESSION['selected_destinations'] as $key => $destination) {
            if ($destination['id'] == $remove_id) {
                unset($_SESSION['selected_destinations'][$key]);
                $_SESSION['selected_destinations'] = array_values($_SESSION['selected_destinations']);
                showAlert('Destination removed from your itinerary.', 'success');
                break;
            }
        }
    }
    
    // Redirect to remove query parameters
    redirect('itinerary.php');
}

// Process save trip form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_trip'])) {
    $trip_name = sanitize($_POST['trip_name']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $budget = floatval($_POST['budget']);
    $notes = sanitize($_POST['notes']);
    
    // Validate input
    if (empty($trip_name) || empty($start_date) || empty($end_date)) {
        showAlert('Please fill all required fields.', 'danger');
    } elseif (strtotime($end_date) < strtotime($start_date)) {
        showAlert('End date cannot be before start date.', 'danger');
    } elseif (!isset($_SESSION['selected_destinations']) || count($_SESSION['selected_destinations']) == 0) {
        showAlert('Please add at least one destination to your itinerary.', 'danger');
    } else {
        // Insert trip
        $sql = "INSERT INTO trips (user_id, name, start_date, end_date, budget, notes) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssds", $_SESSION['user_id'], $trip_name, $start_date, $end_date, $budget, $notes);
        
        if ($stmt->execute()) {
            $trip_id = $stmt->insert_id;
            
            // Insert trip destinations
            $day = 1;
            foreach ($_SESSION['selected_destinations'] as $destination) {
                $destination_id = $destination['id'];
                $sql = "INSERT INTO trip_destinations (trip_id, destination_id, day) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $trip_id, $destination_id, $day);
                $stmt->execute();
                $day++;
            }
            
            // Clear session destinations
            unset($_SESSION['selected_destinations']);
            
            showAlert('Your trip has been saved successfully!', 'success');
            redirect('dashboard.php');
        } else {
            showAlert('Failed to save trip. Please try again.', 'danger');
        }
    }
}

// Get all destinations for dropdown
$destinations = getAllDestinations($conn);

// Set page-specific JS
$page_specific_js = ['js/itinerary.js'];

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Create Your Itinerary</h1>
            <?php displayAlert(); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Selected Destinations</h3>
                    
                    <?php if (isset($_SESSION['selected_destinations']) && count($_SESSION['selected_destinations']) > 0): ?>
                        <div class="selected-destinations">
                            <?php foreach ($_SESSION['selected_destinations'] as $index => $destination): ?>
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?php echo htmlspecialchars($destination['image']); ?>" class="img-fluid rounded-start h-100" alt="<?php echo htmlspecialchars($destination['name']); ?>" style="object-fit: cover;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($destination['name']); ?></h5>
                                                    <span class="badge bg-primary">Day <?php echo $index + 1; ?></span>
                                                </div>
                                                <p class="card-text"><small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($destination['location']); ?></small></p>
                                                <p class="card-text"><?php echo substr(htmlspecialchars($destination['description']), 0, 100); ?>...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a href="destination.php?id=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                                    <a href="itinerary.php?remove=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i> Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Save Trip Form -->
                        <form method="POST" action="" class="mt-4">
                            <h4>Trip Details</h4>
                            <div class="mb-3">
                                <label for="trip_name" class="form-label">Trip Name</label>
                                <input type="text" class="form-control" id="trip_name" name="trip_name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="budget" class="form-label">Budget (₹)</label>
                                <input type="number" class="form-control" id="budget" name="budget" step="0.01" min="0">
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                            <button type="submit" name="save_trip" class="btn btn-primary">Save Trip</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You haven't added any destinations to your itinerary yet. Add some destinations from the list on the right.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Add Destinations</h3>
                    
                    <div class="mb-3">
                        <label for="destinationSearch" class="form-label">Search Destinations</label>
                        <input type="text" class="form-control" id="destinationSearch" placeholder="Search destinations...">
                    </div>
                    
                    <div class="destination-list">
                        <?php foreach ($destinations as $destination): ?>
                            <?php
                            // Skip if already in selected destinations
                            $is_selected = false;
                            if (isset($_SESSION['selected_destinations'])) {
                                foreach ($_SESSION['selected_destinations'] as $selected) {
                                    if ($selected['id'] == $destination['id']) {
                                        $is_selected = true;
                                        break;
                                    }
                                }
                            }
                            
                            if (!$is_selected):
                            ?>
                                <div class="card mb-2 destination-item">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            <img src="<?php echo htmlspecialchars($destination['image']); ?>" class="img-fluid rounded-start h-100" alt="<?php echo htmlspecialchars($destination['name']); ?>" style="object-fit: cover;">
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body py-2 px-3">
                                                <h6 class="card-title mb-0"><?php echo htmlspecialchars($destination['name']); ?></h6>
                                                <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($destination['location']); ?></small></p>
                                                <a href="itinerary.php?add=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>