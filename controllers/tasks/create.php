<?php
function pageCreateController() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $status_id = $_POST['status_id'];
        if ($name == '') {
            echo '<script> alert("Pleas fill in title (description is optional)"); </script>';
        } else {
            saveTask($name, $description, $status_id);

            header('Location: /');
            exit;
        }
    }
    return ['statuses' => getTaskStatuses()];
}