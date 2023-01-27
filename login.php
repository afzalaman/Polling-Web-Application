<?php
session_start();
$error = "";

if(isset($_POST['register'])) {
    if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password_confirm'])) {
        $error = "All fields are required";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else if ($_POST['password'] !== $_POST['password_confirm']) {
        $error = "Passwords do not match";
    } else {
        if(file_exists('users.json')){
            $users = json_decode(file_get_contents('users.json'), true);
        }else{
            $users = array();
        }
        $is_user_exists = false;
        foreach($users as $user) {
            if($user['email'] == $_POST['email']) {
                $is_user_exists = true;
                break;
            }
        }
        if($is_user_exists) {
            $error = "Email already exists";
        } else {
            $users[] = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ];
            file_put_contents('users.json', json_encode($users));
            $error = "Registration successful, you can now login";
        }
        echo $error;
    }
}

if(isset($_POST['login'])) {
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Email and password are required";
    } else {
        if(file_exists('users.json')){
            $users = json_decode(file_get_contents('users.json'), true);
        }else{
            $users = array();
        }
        $emailExists = false;
        $passwordMatch = false;
        foreach($users as $user) {
            if($user['email'] == $_POST['email']) {
                $emailExists = true;
                if(password_verify($_POST['password'], $user['password'])) {
                    $passwordMatch = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true;
                    header("Location: index.php");
                }
                break;
            }
        }
        if(!$emailExists) {
            $error = "Email not found";
        } else if(!$passwordMatch) {
            $error = "Incorrect password";
        }
    }
}
?>
<div>
    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): 
    header('Location: index.php');
    exit;
?>
<div class="success"><?= $success ?></div>
<?php endif; ?>
</div>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username">
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
    <br>
    <label for="password_confirm">Confirm Password:</label>
    <input type="password" name="password_confirm" id="password_confirm">
    <br>
    <input type="submit" name="register" value="Register">
</form>
<br>
<form action="" method="post">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="submit" name="login" value="Login">
</form>
<div>
    <a href="index.php">Back to home</a>
</div>