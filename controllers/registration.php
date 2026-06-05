<?php
function pageRegistrationController() {
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $first_name = $_POST["first_name"] ?? '';
        $last_name  = $_POST["last_name"] ?? '';
        $username   = $_POST["username"] ?? '';
        $email      = $_POST["email"] ?? '';
        $password   = $_POST["password"] ?? '';
        $created_at = date("Y-m-d H:i:s");

        $errors = [];

        if (!validate($first_name, [
            'required' => true,
            'min' => 3,
        ])) {
            $errors['first_name'] = "First name must be at least 3 characters.";
        }

        if (!validate($last_name, [
            'nullable' => true,
            'min' => 3,
        ])) {
            $errors['last_name'] = "Last name must be at least 3 characters.";
        }

        if (!validate($username, [
            'required' => true,
            'min' => 3,
        ])) {
            $errors['username'] = "Username must be at least 3 characters.";
        }

        if (!validate($email, [
            'required' => true,
            'email' => true,
        ])) {
            $errors['email'] = "Invalid email address.";
        }

        if (!validate($password, [
            'required' => true,
            'min' => 6,
        ])) {
            $errors['password'] = "Password must be at least 6 characters.";
        }

        if (empty($errors)) {
            $registered = sendRegistration(
                $first_name,
                $last_name,
                $username,
                $email,
                $password
            );

            if ($registered) {
                header('Location: /');
                exit;
            }
        }
    }

    return ['errors' => $errors, 'data' => $_POST];
}