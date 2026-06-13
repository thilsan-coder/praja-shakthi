<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }

// 1. Fetch Summary Data for Reporting
$total_beneficiaries = $conn->query("SELECT COUNT(*) as count FROM beneficiaries")->fetch_assoc()['count'];
$low_income_count = $conn->query("SELECT COUNT(*) as count FROM beneficiaries WHERE poverty_category='Low Income'")->fetch_assoc()['count'];
$total_projects = $conn->query("SELECT COUNT(*) as count FROM projects")->fetch_assoc()['count'];
$completed_projects = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status='Completed'")->fetch_assoc()['count'];

// 2. Fetch Detailed Data for Tables
$beneficiary_data = $conn->query("SELECT * FROM beneficiaries");
$project_data = $conn->query("SELECT * FROM projects");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .report-container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-box { background: #1a73e8; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-box h2 { margin: 0; font-size: 28px; }
        .stat-box p { margin: 5px 0 0; font-size: 14px; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: left; font-size: 14px; }
        th { background: #f8f9fa; color: #333; }
        .print-btn { background: #2c3e50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; float: right; }
        @media print { .print-btn, .back-link { display: none; } }
    </style>
</head>
<body>

<div class="report-container">
    <a href="dashboard.php" class="back-link" style="text-decoration: none; color: #666;">⬅ Back to Dashboard</a>
    <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    
    <h2 style="margin-top: 20px;">Praja Shakthi - GN Division Summary Report</h2>
    <hr>

    <div class="stats-grid">
        <div class="stat-box">
            <h2><?php echo $total_beneficiaries; ?></h2>
            <p>Total Beneficiaries</p>
        </div>
        <div class="stat-box" style="background: #e67e22;">
            <h2><?php echo $low_income_count; ?></h2>
            <p>Low Income Families</p>
        </div>
        <div class="stat-box" style="background: #27ae60;">
            <h2><?php echo $total_projects; ?></h2>
            <p>Total Projects</p>
        </div>
        <div class="stat-box" style="background: #8e44ad;">
            <h2><?php echo $completed_projects; ?></h2>
            <p>Projects Completed</p>
        </div>
    </div>

    <h3>1. Beneficiary Statistics</h3>
    <table>
        <tr><th>Name</th><th>Category</th><th>Support Status</th></tr>
        <?php while($b = $beneficiary_data->fetch_assoc()): ?>
            <tr><td><?php echo $b['head_of_household']; ?></td><td><?php echo $b['poverty_category']; ?></td><td><?php echo $b['status']; ?></td></tr>
        <?php endwhile; ?>
    </table>

    <h3 style="margin-top: 30px;">2. Development Project Progress</h3>
    <table>
        <tr><th>Project Name</th><th>Budget (LKR)</th><th>Current Status</th></tr>
        <?php while($p = $project_data->fetch_assoc()): ?>
            <tr><td><?php echo $p['project_name']; ?></td><td><?php echo number_format($p['budget'], 2); ?></td><td><?php echo $p['status']; ?></td></tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>