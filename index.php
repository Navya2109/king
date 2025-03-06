<?php
// Database configuration (config.php)
$host = "localhost";
$user = "root";
$password = "";
$dbname = "magicbus_db";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>
magicbus-foundation/
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── config.php
├── admin/
│   ├── dashboard.php
│   └── manage_programs.php
├── programs.php
├── donate.php
├── volunteer.php
├── login.php
├── register.php
└── index.php
CREATE DATABASE magicbus_db;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'volunteer') DEFAULT 'volunteer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE programs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT,
    location VARCHAR(100),
    start_date DATE,
    end_date DATE,
    max_participants INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donor_name VARCHAR(100),
    amount DECIMAL(10,2),
    email VARCHAR(100),
    program_id INT,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE volunteers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    program_id INT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (program_id) REFERENCES programs(id)
);
<?php include 'includes/header.php'; ?>

<div class="hero">
    <h1>Magic Bus Foundation</h1>
    <p>Empowering children through education and sports</p>
</div>

<section class="programs-highlight">
    <h2>Current Programs</h2>
    <div class="program-grid">
        <?php
        $sql = "SELECT * FROM programs ORDER BY start_date DESC LIMIT 3";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo '<div class="program-card">
                <h3>'.$row['title'].'</h3>
                <p>'.substr($row['description'], 0, 100).'...</p>
                <p>Location: '.$row['location'].'</p>
                <a href="programs.php?id='.$row['id'].'">Learn More</a>
            </div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="hero">
    <h1>Magic Bus Foundation</h1>
    <p>Empowering children through education and sports</p>
</div>

<section class="programs-highlight">
    <h2>Current Programs</h2>
    <div class="program-grid">
        <?php
        $sql = "SELECT * FROM programs ORDER BY start_date DESC LIMIT 3";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo '<div class="program-card">
                <h3>'.$row['title'].'</h3>
                <p>'.substr($row['description'], 0, 100).'...</p>
                <p>Location: '.$row['location'].'</p>
                <a href="programs.php?id='.$row['id'].'">Learn More</a>
            </div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="donation-form">
    <h2>Support Our Mission</h2>
    <form action="process_donation.php" method="POST">
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="donor_name" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Amount (₹):</label>
            <input type="number" name="amount" min="100" step="100" required>
        </div>

        <div class="form-group">
            <label>Select Program:</label>
            <select name="program_id">
                <?php
                $result = $conn->query("SELECT id, title FROM programs");
                while($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['title'].'</option>';
                }
                ?>
            </select>
        </div>

        <button type="submit" name="donate">Make Donation</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
<?php
include '../includes/config.php';
// Check admin authentication
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<h2>Admin Dashboard</h2>
<div class="admin-stats">
    <div class="stat-box">
        <h3>Total Programs</h3>
        <?php
        $result = $conn->query("SELECT COUNT(*) AS total FROM programs");
        echo $result->fetch_assoc()['total'];
        ?>
    </div>
    
    <div class="stat-box">
        <h3>Total Donations</h3>
        <?php
        $result = $conn->query("SELECT SUM(amount) AS total FROM donations");
        echo '₹'.$result->fetch_assoc()['total'];
        ?>
    </div>
    
    <div class="stat-box">
        <h3>Total Volunteers</h3>
        <?php
        $result = $conn->query("SELECT COUNT(*) AS total FROM volunteers");
        echo $result->fetch_assoc()['total'];
        ?>
    </div>
</div>

<a href="manage_programs.php" class="admin-link">Manage Programs</a>
<a href="manage_users.php" class="admin-link">Manage Users</a>
