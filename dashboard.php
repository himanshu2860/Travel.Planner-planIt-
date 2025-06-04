<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$page_title = "My Dashboard";

// Get user's trips
$user_trips = getUserTrips($conn, $_SESSION['user_id']);

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-12">
            <h1 class="mb-4">My Dashboard</h1>
            <?php displayAlert(); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="mb-3">Profile Information</h3>
                    <div class="user-profile text-center mb-4">
                        <div class="user-avatar mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="edit_profile.php" class="btn btn-outline-primary">Edit Profile</a>
                        <a href="change_password.php" class="btn btn-outline-secondary">Change Password</a>
                        <?php if ($_SESSION['is_admin'] == 1): ?>
                            <a href="admin/index.php" class="btn btn-outline-danger">Admin Panel</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">My Trips</h3>
                        <a href="itinerary.php" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i> Plan New Trip</a>
                    </div>
                    
                    <?php if (count($user_trips) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Trip Name</th>
                                        <th>Dates</th>
                                        <th>Budget</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_trips as $trip): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trip['name']); ?></td>
                                            <td>
                                                <?php echo date('M d, Y', strtotime($trip['start_date'])); ?> - 
                                                <?php echo date('M d, Y', strtotime($trip['end_date'])); ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo calculateTripDuration($trip['start_date'], $trip['end_date']); ?> days
                                                </small>
                                            </td>
                                            <td><?php echo formatPrice($trip['budget']); ?></td>
                                            <td>
                                                <a href="view_trip.php?id=<?php echo $trip['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                <a href="edit_trip.php?id=<?php echo $trip['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You haven't planned any trips yet. Start planning your first trip!
                        </div>
                        <div class="text-center mt-4">
                            <a href="destinations.php" class="btn btn-primary">Explore Destinations</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Recommended Destinations</h3>
                    
                    <div class="row">
                        <?php
                        // Get 3 random destinations
                        $sql = "SELECT * FROM destinations ORDER BY RAND() LIMIT 3";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0):
                            while ($destination = $result->fetch_assoc()):
                        ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 destination-card">
                                    <img src="<?php echo htmlspecialchars($destination['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($destination['name']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($destination['name']); ?></h5>
                                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($destination['location']); ?></p>
                                        <p class="card-text"><?php echo substr(htmlspecialchars($destination['description']), 0, 100); ?>...</p>
                                        <a href="destination.php?id=<?php echo $destination['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>