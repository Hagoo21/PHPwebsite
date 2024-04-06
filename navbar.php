<?php
// Start session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0); // Set session cookie lifetime to 0 (expires when browser is closed)
    session_start();
}
?>

<nav>
    <a href="index.php"><img src="logo.png" alt="Logo"></a> <!-- Logo acting as home button -->
    <?php
    // Check if the user role is set in the session
    if (isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
    } else {
        $user_role = "";
    }

    // Function to generate navigation links based on user role
    function generate_nav_links($role) {
        switch ($role) {
            case "owner":
                echo '<a href="average_rent.php">Average Rent</a>';
                break;
            case "manager":
                echo '<a href="property_list.php">List Properties</a>';
                echo '<a href="average_rent.php">Average Rent</a>';
                break;
            case "renter":
                echo '<a href="property_list.php">List Properties</a>';
                echo '<a href="update_preferences.php">Update Preferences</a>';
                echo '<a href="rental_groups.php">Rental Groups</a>';
                echo '<a href="average_rent.php">Average Rent</a>';
                break;
            default:
                // Default links for unauthenticated users
                break;
        }
    }

    generate_nav_links($user_role);
    ?>
</nav>
