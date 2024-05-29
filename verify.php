<?php
include "db_conn.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verification_code'])) {
        $verification_code = $_POST['verification_code'];

        
        $stmt = $conn->prepare("SELECT * FROM user WHERE Email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $verification_code); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            $update_stmt = $conn->prepare("UPDATE user SET Status='active' WHERE Email = ?");
            $update_stmt->bind_param("s", $email);
            if ($update_stmt->execute()) {
                header("Location: Loginform.php");
                exit();
            } else {
                echo '<p class="error">Error updating status.</p>';
            }
        } else {
            echo '<p class="error-message">Invalid verification code. Please try again.</p>';
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .btn-black {
            background-color: black;
            color: white;
            border: none;
        }
        .btn.btn-black:focus,
        .btn.btn-black.focus {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
</head>
<body>
    <form action="verify.php?email=<?php echo htmlspecialchars($email); ?>" method="post">
        <div class="container">
            <h2>Verification</h2>
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" name="verification_code" id="verification_code" class="form-control" placeholder="Verification Code" required>
            </div>
            <button type="submit" class="btn btn-primary btn-black">Verify</button>
        </div>
    </form>
</body>
</html>
