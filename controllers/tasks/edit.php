<?php
function pageEditController(): array {
    $id = (int)($_GET['id']);
    $task = GetTaskById($id);

    if (!$task) {
        echo '<p>Task not found.</p>';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST["name"];
        $description = $_POST["description"] ?? "";
        $status_id = $_POST["status_id"];
        $project_id = $task['project_id'];

        saveTask($name, $description, $status_id, $project_id, $id);

        header('Location: /');
        exit;
    }


    return ['id' => $task['id'], 'status_id' => $task['status_id'], 'statuses' => getTaskStatuses(), 'name' => $task['name'], 'description' => $task['description']];
}