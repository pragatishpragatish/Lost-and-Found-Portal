<?php
session_start();

// Database Connection
$host = 'sql213.infinityfree.com';
$username = 'if0_38281715';
$password = 'PrAgAtIsH';
$database = 'if0_38281715_player';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle User Signup
if (isset($_POST['signup'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (email, password) VALUES ('$email', '$password')");
    echo "<script>alert('Signup successful!');</script>";
}

// Handle User Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email == 'admin@admin.com' && $password == 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    }
    
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $email;
            header("Location: index.php");
            exit;
        }
    }
    echo "<script>alert('Invalid login credentials');</script>";
}

// Handle Lost & Found Submissions
if (isset($_POST['submit_item'])) {
    $name = $_POST['name'];
    $item = $_POST['item'];
    $description = $_POST['description'];
    $type = $_POST['type']; // lost or found
    $conn->query("INSERT INTO lost_found (name, item, description, type) VALUES ('$name', '$item', '$description', '$type')");
}

// Handle Deleting Entries (Admin Only)
if (isset($_GET['delete']) && isset($_SESSION['admin'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM lost_found WHERE id=$id");
    header("Location: index.php");
    exit;
}

// Fetch Lost & Found Items
$items = $conn->query("SELECT * FROM lost_found ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lost & Found Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2 class="text-center">Lost & Found Portal</h2>
    
    <?php if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) { ?>
        <form method="POST" class="mb-4">
            <h3>Login / Signup</h3>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control mt-2" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-primary mt-2">Login</button>
            <button type="submit" name="signup" class="btn btn-secondary mt-2">Signup</button>
        </form>
    <?php } else { ?>
        <a href="?logout=true" class="btn btn-danger mb-4">Logout</a>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Item:</label>
                <input type="text" name="item" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description:</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Type:</label>
                <select name="type" class="form-control">
                    <option value="lost">Lost</option>
                    <option value="found">Found</option>
                </select>
            </div>
            <button type="submit" name="submit_item" class="btn btn-primary">Submit</button>
        </form>
    <?php } ?>
    
    <h3 class="text-center">Recent Entries</h3>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Item</th>
            <th>Description</th>
            <th>Type</th>
            <?php if (isset($_SESSION['admin'])) echo '<th>Action</th>'; ?>
        </tr>
        <?php while ($row = $items->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['item']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo ucfirst($row['type']); ?></td>
                <?php if (isset($_SESSION['admin'])) { ?>
                    <td><a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
