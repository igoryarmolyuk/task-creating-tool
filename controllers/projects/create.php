<?php
function pageCreateController() {
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'] ?? '';

        if (!validate($name, ['required' => true])) {
            $errors['name'] = "Name is required";
        }
        else {
            saveProject($name);

            header('Location: /projects');
            exit;
        }
    }
    return ['errors' => $errors, 'data' => $_POST];
}