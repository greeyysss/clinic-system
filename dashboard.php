<?php
session_start();
require_once('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle patient status updates
if (isset($_POST['update_status'])) {
    $patient_id = $_POST['patient_id'];
    $status = $_POST['status'];
    $height = $_POST['height'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $temperature = $_POST['temperature'] ?? null;
    $blood_pressure = $_POST['blood_pressure'] ?? null;

    // determine blood pressure category (high/low/normal) from systolic if given as 'systolic/diastolic'
    $bp_category = null;
    if ($blood_pressure) {
        if (preg_match('/(\d{2,3})\s*\/\s*(\d{2,3})/', $blood_pressure, $m)) {
            $systolic = (int)$m[1];
            if ($systolic >= 140) $bp_category = 'high';
            elseif ($systolic <= 90) $bp_category = 'low';
            else $bp_category = 'normal';
        } else {
            // fallback: allow admin to type 'high'/'low' directly
            $val = strtolower(trim($blood_pressure));
            if (strpos($val, 'high') !== false) $bp_category = 'high';
            elseif (strpos($val, 'low') !== false) $bp_category = 'low';
            elseif ($val !== '') $bp_category = 'normal';
        }
    }

    $sql = "UPDATE patient_registrations SET 
            status = ?, 
            height = ?, 
            weight = ?, 
            temperature = ?, 
            blood_pressure = ?,
            blood_pressure_category = ?,
            check_up_date = CURRENT_TIMESTAMP
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $bp_cat_for_db = $bp_category ?? null;
    $stmt->bind_param("ssssssi", $status, $height, $weight, $temperature, $blood_pressure, $bp_cat_for_db, $patient_id);
    $stmt->execute();
}

// Get pending registrations
$sql = "SELECT * FROM patient_registrations WHERE status = 'pending' ORDER BY registration_date DESC";
$pending_result = $conn->query($sql);

// Get monthly summary using blood_pressure_category
$sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN blood_pressure_category = 'high' THEN 1 ELSE 0 END) as high_blood,
            SUM(CASE WHEN blood_pressure_category = 'low' THEN 1 ELSE 0 END) as low_blood,
            MONTH(check_up_date) as month,
            YEAR(check_up_date) as year
        FROM patient_registrations 
        WHERE status = 'completed' AND check_up_date IS NOT NULL
        GROUP BY YEAR(check_up_date), MONTH(check_up_date)
        ORDER BY year DESC, month DESC";
$summary_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Clinic Management System</title>
    <style>
        /* Add your admin dashboard styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #2c3e50;
        }
        .patient-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .summary-table th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <h2>Pending Registrations</h2>
        <?php while($patient = $pending_result->fetch_assoc()): ?>
            <div class="patient-card">
                <h3><?php echo htmlspecialchars($patient['name']); ?></h3>
                <p>Age: <?php echo htmlspecialchars($patient['age']); ?></p>
                <p>Gender: <?php echo htmlspecialchars($patient['gender']); ?></p>
                <p>Reason: <?php echo htmlspecialchars($patient['reason']); ?></p>
                <p>Registration Date: <?php echo htmlspecialchars($patient['registration_date']); ?></p>

                <form method="POST" action="">
                    <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                    
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" step="0.01" name="height" required>
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" required>
                    </div>

                    <div class="form-group">
                        <label for="temperature">Temperature (°C)</label>
                        <input type="number" step="0.1" name="temperature" required>
                    </div>

                    <div class="form-group">
                        <label for="blood_pressure">Blood Pressure</label>
                        <input type="text" name="blood_pressure" placeholder="e.g., 120/80" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" required>
                            <option value="completed">Complete Check-up</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                    </div>

                    <button type="submit" name="update_status">Update Status</button>
                </form>
            </div>
        <?php endwhile; ?>

        <h2>Monthly Summary</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Month/Year</th>
                    <th>Total Check-ups</th>
                    <th>High Blood Pressure Cases</th>
                    <th>Low Blood Pressure Cases</th>
                </tr>
            </thead>
            <tbody>
                <?php while($summary = $summary_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date("F Y", mktime(0, 0, 0, $summary['month'], 1, $summary['year'])); ?></td>
                        <td><?php echo $summary['total']; ?></td>
                        <td><?php echo $summary['high_blood']; ?></td>
                        <td><?php echo $summary['low_blood']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>