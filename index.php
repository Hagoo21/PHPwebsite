<?php
// Start session to store user login information
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0); // Set session cookie lifetime to 0 (expires when browser is closed)
    session_start();
}

// Include database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['owner_submit'])) {

        // Set user role in session
        $_SESSION['user_role'] = "owner"; 

        // Process owner form submission
        $owner_ID = $_POST['owner_ID'];
        $owner_fname = $_POST['owner_fname'];
        $owner_lname = $_POST['owner_lname'];
        $owner_phone = $_POST['owner_phone'];

        // Insert owner data into database
        $query = "INSERT INTO owner (ID, fname, lname, phone) VALUES (:ID, :fname, :lname, :phone)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':ID' => $owner_ID,
            ':fname' => $owner_fname,
            ':lname' => $owner_lname,
            ':phone' => $owner_phone
        ]);
    } elseif (isset($_POST['manager_submit'])) {

        $_SESSION['user_role'] = "manager";

        // Process manager form submission
        $manager_ID = $_POST['manager_ID'];
        $manager_fname = $_POST['manager_fname'];
        $manager_lname = $_POST['manager_lname'];
        $manager_phone = $_POST['manager_phone'];

        // Insert manager data into database
        $query = "INSERT INTO manager (ID, fname, lname, phone) VALUES (:ID, :fname, :lname, :phone)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':ID' => $manager_ID,
            ':fname' => $manager_fname,
            ':lname' => $manager_lname,
            ':phone' => $manager_phone
        ]);
    } elseif (isset($_POST['renter_submit'])) {

        $_SESSION['user_role'] = "renter";

        // Process renter form submission
        $renter_ID = $_POST['renter_ID'];
        $renter_fname = $_POST['renter_fname'];
        $renter_lname = $_POST['renter_lname'];
        $renter_phone = $_POST['renter_phone'];
        $studentID = $_POST['studentID'];
        $gradYear = $_POST['gradYear'];
        $program = $_POST['program'];

        // Insert renter data into database
        $query = "INSERT INTO renter (ID, fname, lname, phone, studentID, gradYear, program) 
                  VALUES (:ID, :fname, :lname, :phone, :studentID, :gradYear, :program)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':ID' => $renter_ID,
            ':fname' => $renter_fname,
            ':lname' => $renter_lname,
            ':phone' => $renter_phone,
            ':studentID' => $studentID,
            ':gradYear' => $gradYear,
            ':program' => $program
        ]);
    }
}

// Check if logout button is clicked
if (isset($_POST['logout'])) {
    // Destroy session
    session_destroy();
    // Redirect to index.php to refresh the page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Database</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .hidden {
            display: none;
        }
        .submitted {
            border: 2px solid green;
        }
    </style>
</head>
<body>
    <header>
        <h1>Rental Database</h1>
    </header>
    <?php include 'navbar.php'; ?>
    <main>
        <p>Welcome to the Rental Database application.</p>
        <div class="forms-grid">
            <!-- Owner Form -->
            <div class="form-container <?php if ($user_role == "owner") echo 'submitted'; ?>">
                <h2>Owner Information</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="owner_ID">ID:</label>
                    <input type="text" id="owner_ID" name="owner_ID" required><br><br>
                    <label for="owner_fname">First Name:</label>
                    <input type="text" id="owner_fname" name="owner_fname" required><br><br>
                    <label for="owner_lname">Last Name:</label>
                    <input type="text" id="owner_lname" name="owner_lname" required><br><br>
                    <label for="owner_phone">Phone:</label>
                    <input type="text" id="owner_phone" name="owner_phone" required><br><br>
                    <input type="submit" name="owner_submit" value="Submit">
                </form>
            </div>
            <!-- Manager Form -->
            <div class="form-container <?php if ($user_role == "manager") echo 'submitted'; ?>">
                <h2>Manager Information</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="manager_ID">ID:</label>
                    <input type="text" id="manager_ID" name="manager_ID" required><br><br>
                    <label for="manager_fname">First Name:</label>
                    <input type="text" id="manager_fname" name="manager_fname" required><br><br>
                    <label for="manager_lname">Last Name:</label>
                    <input type="text" id="manager_lname" name="manager_lname" required><br><br>
                    <label for="manager_phone">Phone:</label>
                    <input type="text" id="manager_phone" name="manager_phone" required><br><br>
                    <input type="submit" name="manager_submit" value="Submit">
                </form>
            </div>
            <!-- Renter Form -->
            <div class="form-container <?php if ($user_role == "renter") echo 'submitted'; ?>">
                <h2>Renter Information</h2>
                <!-- Add renter form here -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="renter_ID">ID:</label>
                    <input type="text" id="renter_ID" name="renter_ID" required><br><br>
                    <label for="renter_fname">First Name:</label>
                    <input type="text" id="renter_fname" name="renter_fname" required><br><br>
                    <label for="renter_lname">Last Name:</label>
                    <input type="text" id="renter_lname" name="renter_lname" required><br><br>
                    <label for="renter_phone">Phone:</label>
                    <input type="text" id="renter_phone" name="renter_phone" required><br><br>
                    <label for="studentID">Student ID:</label>
                    <input type="text" id="studentID" name="studentID" required><br><br>
                    <label for="gradYear">Graduation Year:</label>
                    <input type="text" id="gradYear" name="gradYear" required><br><br>
                    <label for="program">Program:</label>
                    <input type="text" id="program" name="program" required><br><br>
                    <input type="submit" name="renter_submit" value="Submit">
                </form>
            </div>
        </div>
        <?php if (isset($_SESSION['user_role'])) : ?>
            <!-- Display logout button if session is set -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="submit" name="logout" value="Logout">
            </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Rental Database</p>
    </footer>
</body>
</html>
