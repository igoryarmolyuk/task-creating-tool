<?php
function pageEditController(): array {
    $id = (int)($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST["name"];
        $description = $_POST["description"] ?? "";
        $status_id = $_POST["status_id"];

        saveTask($name, $description, $status_id, $id);

        header('Location: /');
        exit;
    }

    $task = GetTaskById($id);

    if (!$task) {
        echo '<p>Task not found.</p>';
        exit;
    }

    return ['id' => $task['id'], 'status_id' => $task['status_id'], 'statuses' => getTaskStatuses(), 'name' => $task['name'], 'description' => $task['description']];
}