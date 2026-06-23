<?php
function pagePreviewController() {
    if (!isset($_GET['id'])) {
        echo '<p>No task specified.</p>';
        exit;
    }

    $id = (int) $_GET['id'];

    $taskData = GetTaskById($id);
    if ($taskData === null) {
        echo '<p>Task not found.</p>';
        exit;
    }

    $name = $taskData['name'] ?? 'Untitled Task';
    $description = $taskData['description'] ?? 'No description provided.';
    return ['id' => $id, 'name' => $name, 'description' => $description];
}