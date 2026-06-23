<?php

function pageLoginController() {
    global $db_link;

    if (!empty($_SESSION['user_id'])) {
        header('Location: /');
        exit;
    }

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"] ?? '');
        $password = trim($_POST["password"] ?? '');

        if (!validate($username, [
            'required' => true,
            'min' => 3,
        ])) {
            $errors['username'] = "Username must be at least 3 characters.";
        }

        if (!validate($password, [
            'required' => true,
            'min' => 6,
        ])) {
            $errors['password'] = "Password must be at least 6 characters.";
        }

        if (empty($errors)) {
            $username = mysqli_real_escape_string($db_link, $username);
            $hashed_password = md5(mysqli_real_escape_string($db_link, $password));

            $result = mysqli_query(
                $db_link,
                "SELECT * FROM users WHERE username = '$username' LIMIT 1");

            if (($user = mysqli_fetch_assoc($result)) and hash_equals($user['password'], $hashed_password)) {
                $_SESSION['user_id'] = $user['id'];

                header('Location: /');
                exit;
            }

            echo $errors['login'] = 'Invalid username, email, or password.';
        }
    }

    return [
        'errors' => $errors,
    ];
}