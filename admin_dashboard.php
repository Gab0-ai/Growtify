<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT u.id, u.username, u.email, u.created_at,
               p.first_name, p.last_name, p.gender, p.age, p.goal,
               p.weight, p.height, p.bmi, p.target_weight
        FROM users u
        LEFT JOIN user_profiles p ON u.id = p.user_id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; }
        h2 { text-align: center; }
        table {
            border-collapse: collapse; width: 95%; margin: 20px auto;
            background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd; padding: 8px; text-align: center;
        }
        th { background-color: #28a745; color: white; }
        a.logout {
            display: block; text-align: center; margin: 20px auto;
            width: 150px; padding: 10px; background: #dc3545; color: white; text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Created At</th>
            <th>First Name</th><th>Last Name</th><th>Gender</th><th>Age</th><th>Goal</th>
            <th>Weight (kg)</th><th>Height (cm)</th><th>BMI</th><th>Target Weight (kg)</th>
        </tr>
        <?php foreach ($users as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td><?= htmlspecialchars($row['first_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['last_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['age'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['goal'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['weight'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['height'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['bmi'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['target_weight'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_logout.php" class="logout">Logout</a>
</body>
</html>
