<?php
function pageCreateController() {
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $status_id = $_POST['status_id'];

        if (!validate($name, ['required' => true])) {
            $errors['name'] = "Name is required";
        }
        else {
            saveTask($name, $description, $status_id);

            header('Location: /');
            exit;
        }
    }
    return ['statuses' => getTaskStatuses(), 'errors' => $errors, 'data' => $_POST];
}