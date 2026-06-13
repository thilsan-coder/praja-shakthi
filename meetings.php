<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }

$msg = "";

// 1. Logic to Add Meeting
if (isset($_POST['add_meeting'])) {
    $title = $_POST['title'];
    $date = $_POST['m_date'];
    $time = $_POST['m_time'];
    $loc = $_POST['location'];
    $agenda = $_POST['agenda'];

    $sql = "INSERT INTO meetings (meeting_title, meeting_date, meeting_time, location, agenda) 
            VALUES ('$title', '$date', '$time', '$loc', '$agenda')";
    if($conn->query($sql)) { $msg = "Meeting Scheduled Successfully!"; }
}

// 2. Logic to Update Meeting
if (isset($_POST['update_meeting'])) {
    $id = $_POST['m_id'];
    $title = $_POST['title'];
    $date = $_POST['m_date'];
    $time = $_POST['m_time'];
    $loc = $_POST['location'];
    $agenda = $_POST['agenda'];

    $sql = "UPDATE meetings SET meeting_title='$title', meeting_date='$date', meeting_time='$time', location='$loc', agenda='$agenda' WHERE id=$id";
    if($conn->query($sql)) { $msg = "Meeting Details Updated!"; }
}

// 3. Search Logic
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $meeting_list = $conn->query("SELECT * FROM meetings WHERE meeting_title LIKE '%$search%' OR meeting_date LIKE '%$search%' ORDER BY meeting_date ASC");
} else {
    $meeting_list = $conn->query("SELECT * FROM meetings ORDER BY meeting_date ASC");
}

if (isset($_GET['delete_meeting'])) {
    $del_id = $_GET['delete_meeting'];
    $sql = "DELETE FROM meetings WHERE id = $del_id";
    if ($conn->query($sql) === TRUE) {
        $msg = "<p style='color:red;'>Success: Meeting Schedule Deleted!</p>";
    }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meetings - Praja Shakthi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 850px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .meeting-card { border-left: 5px solid #e67e22; background: #fffcf0; padding: 15px; margin-bottom: 15px; border-radius: 8px; position: relative; }
        .edit-btn { position: absolute; top: 15px; right: 15px; background: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .add-form { background: #fdf2e9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #fab1a0; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #e67e22; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" style="text-decoration: none; color: #666;">⬅ Back to Dashboard</a>
    <h2>📅 Community Meeting Schedules</h2>
    <p style="color: green; font-weight: bold;"><?php echo $msg; ?></p>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Search by Date (YYYY-MM-DD) or Title..." value="<?php echo $search; ?>">
        <button type="submit" class="btn" style="background:#d35400;">Search</button>
        <a href="meetings.php" style="padding:10px; text-decoration:none; background:#eee; border-radius:5px; color:#333;">Clear</a>
    </form>

    <?php if($_SESSION['role'] == 'GN_Officer' || $_SESSION['role'] == 'Council_Member'): ?>
    <div class="add-form">
        <h4 id="form-title">Schedule a New Meeting</h4>
        <form method="POST">
            <input type="hidden" name="m_id" id="m_id">
            <input type="text" name="title" id="m_title" placeholder="Meeting Title" required>
            <div style="display:flex; gap:10px;">
                <input type="date" name="m_date" id="m_date" required>
                <input type="time" name="m_time" id="m_time" required>
            </div>
            <input type="text" name="location" id="m_loc" placeholder="Location">
            <textarea name="agenda" id="m_agenda" placeholder="Agenda"></textarea>
            
            <button type="submit" name="add_meeting" id="add-btn" class="btn">Schedule Meeting</button>
            <button type="submit" name="update_meeting" id="update-btn" class="btn" style="display:none; background:#27ae60;">Update Meeting</button>
            <button type="button" onclick="resetForm()" id="cancel-btn" style="display:none; padding:10px; border:none; border-radius:5px; cursor:pointer;">Cancel</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="meeting-list">
        <?php while($row = $meeting_list->fetch_assoc()): ?>
        <div class="meeting-card">
            <span style="color: #d35400; font-weight: bold;">🗓 <?php echo $row['meeting_date']; ?> at <?php echo $row['meeting_time']; ?></span>
            <h3><?php echo $row['meeting_title']; ?></h3>
            <p>📍 <strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p style="font-size: 14px; color: #555;"><strong>Agenda:</strong> <?php echo $row['agenda']; ?></p>

            <?php if($_SESSION['role'] == 'GN_Officer'): ?>
<td>
    <button class="edit-btn" onclick='editMeeting(<?php echo json_encode($row); ?>)'>✏️ Edit</button>
    
    <a href="meetings.php?delete_meeting=<?php echo $row['id']; ?>" 
       onclick="return confirm('Intha meeting schedule-ah delete panna nichayama irukkingala?');"
       style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; margin-left: 5px;">
       🗑️ Delete
    </a>
</td>
<?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function editMeeting(data) {
        document.getElementById('form-title').innerText = "Edit Meeting: " + data.meeting_title;
        document.getElementById('m_id').value = data.id;
        document.getElementById('m_title').value = data.meeting_title;
        document.getElementById('m_date').value = data.meeting_date;
        document.getElementById('m_time').value = data.meeting_time;
        document.getElementById('m_loc').value = data.location;
        document.getElementById('m_agenda').value = data.agenda;

        document.getElementById('add-btn').style.display = "none";
        document.getElementById('update-btn').style.display = "inline-block";
        document.getElementById('cancel-btn').style.display = "inline-block";
        window.scrollTo(0, 0);
    }

    function resetForm() {
        document.getElementById('form-title').innerText = "Schedule a New Meeting";
        document.getElementById('m_id').value = "";
        document.getElementById('m_title').value = "";
        document.getElementById('m_date').value = "";
        document.getElementById('m_time').value = "";
        document.getElementById('m_loc').value = "";
        document.getElementById('m_agenda').value = "";

        document.getElementById('add-btn').style.display = "inline-block";
        document.getElementById('update-btn').style.display = "none";
        document.getElementById('cancel-btn').style.display = "none";
    }
</script>

</body>
</html>