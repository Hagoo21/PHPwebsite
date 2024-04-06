<?php
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postalCode'];
    $type = $_POST['type'];
    $beds = $_POST['beds'];
    $bath = $_POST['bath'];
    $cost = $_POST['cost'];
    $apartmentNum = $_POST['apartmentNum'];
    $dateListed = $_POST['dateListed'];
    $parking = $_POST['parking'];
    $access = $_POST['access'];
    $laundry = $_POST['laundry'];
    $managerID = $_POST['managerID'];

    // Check if managerID exists in the manager table
    $query = "SELECT ID FROM manager WHERE ID = :managerID";
    $statement = $pdo->prepare($query);
    $statement->execute([':managerID' => $managerID]);
    $managerExists = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$managerExists) {
        // Handle case where managerID doesn't exist
        echo "Error: Manager ID does not exist.";
        exit();
    }

    // Insert data into property table
    $query = "INSERT INTO property (street, city, province, postalCode, type, beds, bath, cost, apartmentNum, dateListed, parking, access, laundry, managerID) 
              VALUES (:street, :city, :province, :postalCode, :type, :beds, :bath, :cost, :apartmentNum, :dateListed, :parking, :access, :laundry, :managerID)";
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':street' => $street,
        ':city' => $city,
        ':province' => $province,
        ':postalCode' => $postalCode,
        ':type' => $type,
        ':beds' => $beds,
        ':bath' => $bath,
        ':cost' => $cost,
        ':apartmentNum' => $apartmentNum,
        ':dateListed' => $dateListed,
        ':parking' => $parking,
        ':access' => $access,
        ':laundry' => $laundry,
        ':managerID' => $managerID
    ]);

    // Redirect to property_list.php to refresh the page
    header("Location: property_list.php");
    exit();
}

// Fetch property data with owner and manager information
$query = "SELECT p.code, p.street, p.city, p.province, p.postalCode, p.type, p.beds, p.bath, p.cost, 
                 o.fname AS owner_fname, o.lname AS owner_lname, m.fname AS manager_fname, m.lname AS manager_lname
          FROM property p
          LEFT JOIN ownsProperty op ON p.code = op.propertyID
          LEFT JOIN owner o ON op.ownerID = o.ID
          LEFT JOIN manager m ON p.managerID = m.ID";
$statement = $pdo->prepare($query);
$statement->execute();
$properties = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .property-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .property {
            border: 1px solid #ccc;
            padding: 10px;
            width: calc(25% - 20px); /* 4 properties per row */
            box-sizing: border-box;
        }

        .property img {
            max-width: 100%;
            height: auto;
        }

        .property-details {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Property List</h1>
    </header>
    <?php include 'navbar.php'; ?>
    <main>
        <div class="property-container">
            <?php foreach ($properties as $property): ?>
                <div class="property">
                    <img src="<?php echo $property['image']; ?>" alt="Property Image">
                    <div class="property-details">
                        <p><strong>Property ID:</strong> <?php echo $property['code']; ?></p>
                        <p><strong>Owner:</strong> <?php echo $property['owner_fname'] . ' ' . $property['owner_lname']; ?></p>
                        <p><strong>Manager:</strong> <?php echo isset($property['manager_fname']) ? $property['manager_fname'] . ' ' . $property['manager_lname'] : 'N/A'; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Add New Property</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="street">Street:</label>
            <input type="text" id="street" name="street" required><br><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required><br><br>
            <label for="province">Province:</label>
            <input type="text" id="province" name="province" required><br><br>
            <label for="postalCode">Postal Code:</label>
            <input type="text" id="postalCode" name="postalCode" required><br><br>
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="House">House</option>
                <option value="Apartment">Apartment</option>
                <option value="Room">Room</option>
            </select><br><br>
            <label for="beds">Beds:</label>
            <input type="number" id="beds" name="beds" min="0" required><br><br>
            <label for="bath">Baths:</label>
            <input type="number" id="bath" name="bath" min="0" required><br><br>
            <label for="cost">Cost:</label>
            <input type="number" id="cost" name="cost" min="0" step="0.01" required><br><br>
            <label for="apartmentNum">Apartment Number:</label>
            <input type="number" id="apartmentNum" name="apartmentNum" min="0" required><br><br>
            <label for="dateListed">Date Listed:</label>
            <input type="date" id="dateListed" name="dateListed" required><br><br>
            <label for="parking">Parking:</label>
            <select id="parking" name="parking" required>
                <option value="Y">Yes</option>
                <option value="N">No</option>
            </select><br><br>
            <label for="access">Access:</label>
            <select id="access" name="access" required>
                <option value="Y">Yes</option>
                <option value="N">No</option>
            </select><br><br>
            <label for="laundry">Laundry:</label>
            <select id="laundry" name="laundry" required>
                <option value="ensuite">Ensuite</option>
                <option value="shared">Shared</option>
            </select><br><br>
            <label for="managerID">Manager ID:</label>
            <input type="text" id="managerID" name="managerID" required><br><br>
            <input type="submit" value="Add Property">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Rental Database</p>
    </footer>
</body>
</html>
