<?php
include 'db.php';
session_start();

// Check if user is GN Officer
if ($_SESSION['role'] !== 'GN_Officer') {
    die("Access Denied: Only Grama Niladhari can edit this profile.");
}

// 1. Fetch profile data (First row mattum edukkurom)
$result = $conn->query("SELECT * FROM gn_profile LIMIT 1");
$profile = $result->fetch_assoc();

// Profile database-la illati, dummy values set pandrom
if (!$profile) {
    $profile = [
        'division_name' => 'Not Set',
        'division_number' => 'Not Set',
        'population' => 0,
        'households' => 0,
        'contact_no' => 'Not Set'
    ];
}

// 2. Update Logic
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $pop = $_POST['population'];
    $house = $_POST['households'];
    $contact = $_POST['contact'];

    // Database-la profile irundha Update pannu, illati Insert pannu
    $check = $conn->query("SELECT id FROM gn_profile LIMIT 1");
    if ($check->num_rows > 0) {
        $sql = "UPDATE gn_profile SET division_name='$name', division_number='$number', population=$pop, households=$house, contact_no='$contact' WHERE id > 0 LIMIT 1";
    } else {
        $sql = "INSERT INTO gn_profile (division_name, division_number, population, households, contact_no) VALUES ('$name', '$number', '$pop', '$house', '$contact')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php?msg=Profile Updated Successfully!");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GN Profile - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 40px; }
        .form-card { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;}
        button { background: #1a73e8; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px;}
        .msg { color: green; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

<div class="form-card">
    <a href="dashboard.php" style="text-decoration: none; color: #1a73e8;">⬅ Back to Dashboard</a>
    <h2>GN Division Profile</h2>
    
    <?php if(isset($_GET['msg'])) echo "<p class='msg'>".$_GET['msg']."</p>"; ?>
    
    <form method="POST" action="">
        <label>Division Name</label>
        <input type="text" name="name" value="<?php echo $profile['division_name']; ?>" required>
        
        <label>Division Number</label>
        <input type="text" name="number" value="<?php echo $profile['division_number']; ?>" required>
        
        <label>Total Population</label>
        <input type="number" name="population" value="<?php echo $profile['population']; ?>" required>
        
        <label>Households</label>
        <input type="number" name="households" value="<?php echo $profile['households']; ?>" required>
        
        <label>Contact Details</label>
        <input type="text" name="contact" value="<?php echo $profile['contact_no']; ?>" required>
        
        <button type="submit" name="update_profile">Update Profile Info</button>
    </form>
</div>

</body>
</html>