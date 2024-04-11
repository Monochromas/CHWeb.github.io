
Here's an example HTML form for the username and password:

html
Copy code
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
And here's an example PHP script (login.php) to handle the login process:

php
Copy code
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
