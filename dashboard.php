<?php
session_start();
// Login pannama direct-ah dashboard vara mudiyatha maari pannanum
include 'db.php';
$house_count = $conn->query("SELECT COUNT(*) as c FROM beneficiaries")->fetch_assoc()['c'];
$proj_count = $conn->query("SELECT COUNT(*) as c FROM projects WHERE status='Ongoing'")->fetch_assoc()['c'];
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];

// Poverty Categories count edukkurom (Charts-kkaga)
$low_count = $conn->query("SELECT COUNT(*) as c FROM beneficiaries WHERE poverty_category='Low Income'")->fetch_assoc()['c'];
$mid_count = $conn->query("SELECT COUNT(*) as c FROM beneficiaries WHERE poverty_category='Middle Income'")->fetch_assoc()['c'];
$vul_count = $conn->query("SELECT COUNT(*) as c FROM beneficiaries WHERE poverty_category='Vulnerable'")->fetch_assoc()['c'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        /* Sidebar Styles */
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; padding: 20px; position: fixed; }
        .sidebar h2 { font-size: 20px; border-bottom: 1px solid #555; padding-bottom: 10px; }
        .sidebar a { display: block; color: #bdc3c7; padding: 12px; text-decoration: none; margin-bottom: 5px; border-radius: 4px; }
        .sidebar a:hover { background: #34495e; color: white; }
        .sidebar a {
    display: block;
    color: #bdc3c7;
    padding: 12px;
    text-decoration: none;
    margin-bottom: 5px;
    border-radius: 4px;
    cursor: pointer; /* Idhu kandippa irukkanum */
}
        
        /* Main Content */
        .main-content { margin-left: 270px; padding: 30px; width: 100%; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin: -30px -30px 30px -30px; }
        
        /* Dashboard Cards */
        .card-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #1a73e8; }
        .card h3 { margin: 0; color: #777; font-size: 14px; }
        .card p { font-size: 24px; font-weight: bold; margin: 10px 0 0; color: #333; }
        
        .logout-btn { background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="sidebar">
       
    <h2>Praja Shakthi</h2>
    <p style="font-size: 12px; color: #1abc9c;">Logged in as: <?php echo str_replace('_', ' ', $role); ?></p>
    
    <a href="dashboard.php">🏠 Dashboard</a>
    
    <?php if($role == 'GN_Officer' || $role == 'Council_Member'): ?>
        <a href="beneficiaries.php">📋 Beneficiary List</a>
        <a href="meetings.php">📅 Meeting Schedules</a>
    <?php endif; ?>

    <?php if($role == 'GN_Officer'): ?>
        <a href="profile.php">⚙️ GN Division Profile</a>
        <a href="beneficiaries.php">➕ Add New Records</a>
    <?php endif; ?>

    <a href="projects.php">📁 Development Projects</a>
    
    <a href="reports.php">📊 Reports</a>

    
</div>
    </div>

    <div class="main-content">
        <div class="header">
            <h3>Welcome, <?php echo $user; ?>!</h3>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>Total Households</h3>
                <p>1,250</p> </div>
            <div class="card" style="border-left-color: #27ae60;">
                <h3>Active Projects</h3>
                <p>08</p>
            </div>
            <div class="card" style="border-left-color: #f1c40f;">
                <h3>Pending Requests</h3>
                <p>12</p>
            </div>
        </div>

        <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 8px;">
            <h4>Recent Project Updates</h4>
            <p style="color: #666;">No recent updates found. Data will be fetched from MySQL soon.</p>
        </div>

        
    </div>

</body>
</html>