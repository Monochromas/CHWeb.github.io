<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password using SHA-512
    $hashedPassword = hash('sha512', $password);

    // Read the JSON file
    $users = json_decode(file_get_contents('users.json'), true);

    // Check if the user exists and the password is correct
    if (isset($users[$username]) && $users[$username]["password"] === $hashedPassword) {
        // Generate a random hash for the cookie
        $cookieHash = bin2hex(random_bytes(32));
        
        // Set the cookie with expiry time
        $expiryTime = time() + (86400 * 30); // 30 days
        setcookie("auth_cookie", $cookieHash, $expiryTime, "/");

        // Update the JSON file with the new cookie hash
        $users[$username]["cookie"] = $cookieHash;
        $users[$username]["expiry"] = $expiryTime;
        file_put_contents('users.json', json_encode($users));

        echo "Login successful! Cookie set.";
    } else {
        echo "Invalid username or password.";
    }
}
?>
