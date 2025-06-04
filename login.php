<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$page_title = "Login";
$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check if username exists
        $sql = "SELECT id, username, email, password, is_admin FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                // Redirect to dashboard
                redirect('dashboard.php');
            } else {
                $error = 'Invalid password.';
            }
        } else {
            $error = 'Username not found.';
        }
    }
}

include 'includes/header.php';
?>
<div class="parallax-section">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h2>Login</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
<style>
  .parallax-section {
    background-image: url('img/mandi_shivratri_fair.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 15px;
  }

  .form-container {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff; /* ✅ Makes labels, headings white */
  }

  .form-container label {
    color: #fff; /* ✅ Labels */
  }

  .form-container input::placeholder {
    color: rgba(255, 255, 255, 0.7); /* ✅ Placeholder text */
  }

  .form-container a {
    color: #aad8ff; /* ✅ Light blue for links */
  }
</style>

<?php include 'includes/footer.php'; ?>