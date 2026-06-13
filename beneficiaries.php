<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }

$msg = "";

// 1. Logic to Add Beneficiary
if (isset($_POST['add_btn'])) {
    $house = $_POST['house_no'];
    $name = $_POST['head_name'];
    $cat = $_POST['category'];
    $support = $_POST['support'];

    $sql = "INSERT INTO beneficiaries (house_no, head_of_household, poverty_category, support_received) 
            VALUES ('$house', '$name', '$cat', '$support')";
    if ($conn->query($sql) === TRUE) {
        $msg = "<p style='color:green;'>Success: Member Added!</p>";
    }
}

// 2. Logic to Update Beneficiary
if (isset($_POST['update_btn'])) {
    $id = $_POST['b_id'];
    $house = $_POST['house_no'];
    $name = $_POST['head_name'];
    $cat = $_POST['category'];
    $support = $_POST['support'];

    $sql = "UPDATE beneficiaries SET house_no='$house', head_of_household='$name', poverty_category='$cat', support_received='$support' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $msg = "<p style='color:green;'>Success: Beneficiary Updated!</p>";
    }
}

// 3. Search Logic
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $list = $conn->query("SELECT * FROM beneficiaries WHERE head_of_household LIKE '%$search%' OR house_no LIKE '%$search%'");
} else {
    $list = $conn->query("SELECT * FROM beneficiaries");
}

// 4. Logic to Delete Beneficiary
if (isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    $sql = "DELETE FROM beneficiaries WHERE id = $del_id";
    if ($conn->query($sql) === TRUE) {
        $msg = "<p style='color:red;'>Success: Beneficiary Deleted!</p>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beneficiaries - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        input, select { padding: 10px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; width: 100%; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #1a73e8; color: white; }
        .form-box { background: #eef2f7; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .btn { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
        .edit-btn { background: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" style="text-decoration: none; color: #666;">⬅ Back to Dashboard</a>
    <h2>Beneficiary Management</h2>
    <?php echo $msg; ?>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Search by Name or House No..." value="<?php echo $search; ?>">
        <button type="submit" class="btn" style="background:#1a73e8;">Search</button>
        <a href="beneficiaries.php" style="padding:10px; background:#ccc; text-decoration:none; border-radius:5px; color:black;">Clear</a>
    </form>

    <?php if($_SESSION['role'] == 'GN_Officer'): ?>
    <div class="form-box">
        <h4 id="form-title">Add New Household</h4>
        <form method="POST">
            <input type="hidden" name="b_id" id="b_id">
            <input type="text" name="house_no" id="house_no" placeholder="House No" required>
            <input type="text" name="head_name" id="head_name" placeholder="Head of Household" required>
            <select name="category" id="category">
                <option value="Low Income">Low Income</option>
                <option value="Middle Income">Middle Income</option>
                <option value="Vulnerable">Vulnerable</option>
            </select>
            <input type="text" name="support" id="support" placeholder="Support Type">
            
            <button type="submit" name="add_btn" id="add_btn" class="btn">Add Beneficiary</button>
            <button type="submit" name="update_btn" id="update_btn" class="btn" style="display:none; background:#27ae60;">Update Details</button>
            <button type="button" onclick="resetForm()" id="cancel_btn" style="display:none; padding:10px; border:none; border-radius:5px;">Cancel</button>
        </form>
    </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>House No</th>
            <th>Name</th>
            <th>Category</th>
            <th>Support</th>
            <?php if($_SESSION['role'] == 'GN_Officer'): ?> <th>Action</th> <?php endif; ?>
        </tr>
        <?php while($row = $list->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['house_no']; ?></td>
            <td><?php echo $row['head_of_household']; ?></td>
            <td><?php echo $row['poverty_category']; ?></td>
            <td><?php echo $row['support_received']; ?></td>
            <?php if($_SESSION['role'] == 'GN_Officer'): ?>
           
            <td>
    <button class="edit-btn" onclick='editBeneficiary(<?php echo json_encode($row); ?>)'>✏️ Edit</button>
    
    <a href="beneficiaries.php?delete=<?php echo $row['id']; ?>" 
       onclick="return confirm('Intha beneficiary details-ah delete panna nichayama irukkingala?');"
       style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; margin-left: 5px;">
       🗑️ Delete
    </a>
</td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function editBeneficiary(data) {
        document.getElementById('form-title').innerText = "Edit Beneficiary: " + data.head_of_household;
        document.getElementById('b_id').value = data.id;
        document.getElementById('house_no').value = data.house_no;
        document.getElementById('head_name').value = data.head_of_household;
        document.getElementById('category').value = data.poverty_category;
        document.getElementById('support').value = data.support_received;

        document.getElementById('add_btn').style.display = "none";
        document.getElementById('update_btn').style.display = "inline-block";
        document.getElementById('cancel_btn').style.display = "inline-block";
        window.scrollTo(0,0);
    }

    function resetForm() {
        document.getElementById('form-title').innerText = "Add New Household";
        document.getElementById('b_id').value = "";
        document.getElementById('house_no').value = "";
        document.getElementById('head_name').value = "";
        document.getElementById('support').value = "";
        
        document.getElementById('add_btn').style.display = "inline-block";
        document.getElementById('update_btn').style.display = "none";
        document.getElementById('cancel_btn').style.display = "none";
    }
</script>

</body>
</html>