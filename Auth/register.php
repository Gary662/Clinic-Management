<?php
include '../config/db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure form fields are set using null coalescing operator to avoid undefined index warning
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    // New fields for patient registration
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $sex = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $health_care_number = $_POST['health_care_number'] ?? '';

    // Check if all fields are filled
    if (!$name || !$email || !$password || !$role || !$phone || !$address || !$sex || !$age || !$dob || !$health_care_number) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists.";
        } else {
            // Hash the password before saving
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user data into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone, address, gender, age, dob, health_care_number) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $name, $email, $hash, $role, $phone, $address, $sex, $age, $dob, $health_care_number);
            $stmt->execute();
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Register</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="form-group">
        <input type="text" name="name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        
        <!-- New fields for patient registration -->
        <input type="text" name="phone" placeholder="Phone Number" class="form-control mb-2" required>
        <input type="text" name="address" placeholder="Address" class="form-control mb-2" required>
        
        <select name="gender" class="form-control mb-2" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        
        <input type="number" name="age" placeholder="Age" class="form-control mb-2" required>
        <input type="date" name="dob" placeholder="Date of Birth" class="form-control mb-2" required>
        <input type="text" name="health_care_number" placeholder="Health Care Number" class="form-control mb-2" required>
        
        <select name="role" class="form-control mb-2">
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
            <option value="admin">Admin</option>
        </select>
        
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    
    <a href="../index.php" class="btn btn-secondary mt-3">← Back</a>
</body>
</html>
