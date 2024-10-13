<?php
// Check if form data is sent
if (isset($_POST['adname']) && isset($_POST['adsurname']) && isset($_POST['adusername']) && isset($_POST['adpass']) && isset($_POST['adposition'])) {
    // Include your database connection file
    include '../connect.php'; // Adjust the path if necessary

    // Get form data
    $first_name = $_POST['adname'];
    $last_name = $_POST['adsurname'];
    $username = $_POST['adusername'];
    $password = $_POST['adpass'];
    $position = $_POST['adposition'];

    // Sanitize input to prevent SQL injection
    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $position = $conn->real_escape_string($position);

    // Check if username, first name, and last name already exist
    $check_sql = "SELECT * FROM admin WHERE adusername = '$username' OR (adname = '$first_name' AND adsurname = '$last_name')";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If user already exists, return an error
        echo json_encode(array('success' => false, 'error' => 'User already registered. Please use another username or name.'));
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // SQL query to insert a new user
        $insert_sql = "INSERT INTO admin (adname, adsurname, adusername, adpass, adposition) VALUES ('$first_name', '$last_name', '$username', '$hashed_password', '$position')";

        if ($conn->query($insert_sql) === TRUE) {
            // Fetch the updated user list
            $fetch_sql = "SELECT adid, adname, adsurname, adusername, adpass, adposition FROM admin";
            $fetch_result = $conn->query($fetch_sql);

            $users = array();
            if ($fetch_result) {
                while ($row = $fetch_result->fetch_assoc()) {
                    $users[] = $row;
                }
            }

            // Send success response with user data
            echo json_encode(array('success' => true, 'users' => $users));
        } else {
            // Respond with error message
            echo json_encode(array('success' => false, 'error' => 'Database Error: ' . $conn->error));
        }
    }

    $conn->close();
} else {
    // If form data is not set, return an error message
    echo json_encode(array('success' => false, 'error' => 'Required parameters are not set'));
}
?>
