<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }

$msg = "";

// 1. Logic to Add Project
if (isset($_POST['add_project'])) {
    $name = $_POST['project_name'];
    $desc = $_POST['description'];
    $budget = $_POST['budget'];
    $status = $_POST['status'];

    $sql = "INSERT INTO projects (project_name, description, budget, status) VALUES ('$name', '$desc', '$budget', '$status')";
    if($conn->query($sql)) { $msg = "Project Added!"; }
}

// 2. Logic to Update/Edit Project
if (isset($_POST['update_project'])) {
    $id = $_POST['project_id'];
    $name = $_POST['project_name'];
    $desc = $_POST['description'];
    $budget = $_POST['budget'];
    $status = $_POST['status'];

    $sql = "UPDATE projects SET project_name='$name', description='$desc', budget='$budget', status='$status' WHERE id=$id";
    if($conn->query($sql)) { $msg = "Project Updated!"; }
}

// 3. Search Logic
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $project_list = $conn->query("SELECT * FROM projects WHERE project_name LIKE '%$search%' ORDER BY created_at DESC");
} else {
    $project_list = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
}

// Logic to Delete Project
if (isset($_GET['delete_project'])) {
    $del_id = $_GET['delete_project'];
    $sql = "DELETE FROM projects WHERE id = $del_id";
    if ($conn->query($sql) === TRUE) {
        $msg = "<p style='color:red;'>Success: Project Deleted!</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .project-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .project-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; position: relative; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; float: right; }
        .Planned { background: #ffeaa7; color: #d63031; }
        .Ongoing { background: #81ecec; color: #0984e3; }
        .Completed { background: #55efc4; color: #00b894; }
        .edit-btn { background: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        .form-box { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #ddd; }
        input, textarea, select { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" style="text-decoration: none; color: #666;">⬅ Back to Dashboard</a>
    <h2>Community Development Projects</h2>
    <p style="color: green; font-weight: bold;"><?php echo $msg; ?></p>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Search Projects by name..." value="<?php echo $search; ?>">
        <button type="submit" class="btn">Search</button>
        <a href="projects.php" style="padding: 10px; text-decoration: none; background: #eee; border-radius: 5px; color: #333;">Clear</a>
    </form>

    <?php if($_SESSION['role'] == 'GN_Officer' || $_SESSION['role'] == 'Council_Member'): ?>
    <div class="form-box" id="project-form-container">
        <h4 id="form-title">Plan New Project</h4>
        <form method="POST">
            <input type="hidden" name="project_id" id="p_id">
            <input type="text" name="project_name" id="p_name" placeholder="Project Name" required>
            <textarea name="description" id="p_desc" placeholder="Description"></textarea>
            <input type="number" name="budget" id="p_budget" placeholder="Budget (LKR)">
            <select name="status" id="p_status">
                <option value="Planned">Planned</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
            </select>
            <button type="submit" name="add_project" id="submit-btn" class="btn">Create Project</button>
            <button type="submit" name="update_project" id="update-btn" class="btn" style="display:none; background:#27ae60;">Update Project</button>
            <button type="button" onclick="resetForm()" id="cancel-btn" style="display:none; padding:10px; border:none; border-radius:5px; cursor:pointer;">Cancel</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="project-grid">
        <?php while($row = $project_list->fetch_assoc()): ?>
        <div class="project-card">
            <span class="status-badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span>
            <h3><?php echo $row['project_name']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p><strong>Budget:</strong> LKR <?php echo number_format($row['budget'], 2); ?></p>
            
            <?php if($_SESSION['role'] == 'GN_Officer'): ?>
                <button class="edit-btn" onclick="editProject(<?php echo htmlspecialchars(json_encode($row)); ?>)">✏️ Edit</button>
                <a href="projects.php?delete_project=<?php echo $row['id']; ?>" 
       onclick="return confirm('Intha project details-ah delete panna nichayama irukkingala?');"
       style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; margin-left: 5px;">
       🗑️ Delete
    </a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function editProject(data) {
        document.getElementById('form-title').innerText = "Edit Project: " + data.project_name;
        document.getElementById('p_id').value = data.id;
        document.getElementById('p_name').value = data.project_name;
        document.getElementById('p_desc').value = data.description;
        document.getElementById('p_budget').value = data.budget;
        document.getElementById('p_status').value = data.status;

        document.getElementById('submit-btn').style.display = "none";
        document.getElementById('update-btn').style.display = "inline-block";
        document.getElementById('cancel-btn').style.display = "inline-block";
        
        window.scrollTo(0, 0); // Scroll to top to see the form
    }

    function resetForm() {
        document.getElementById('form-title').innerText = "Plan New Project";
        document.getElementById('p_id').value = "";
        document.getElementById('p_name').value = "";
        document.getElementById('p_desc').value = "";
        document.getElementById('p_budget').value = "";
        
        document.getElementById('submit-btn').style.display = "inline-block";
        document.getElementById('update-btn').style.display = "none";
        document.getElementById('cancel-btn').style.display = "none";
    }
</script>

</body>
</html>