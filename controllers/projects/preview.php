<?php
function pagePreviewController() {
    if (!isset($_GET['id'])) {
        echo '<p>No Project specified.</p>';
        exit;
    }

    $id = (int) $_GET['id'];

    $projectData = GetProjectById($id);
    if ($projectData === null) {
        echo '<p>Project not found.</p>';
        exit;
    }

    $taskCount = getTaskCount($id);
    $userCount = getUserCount($id);

    return ['project' => $projectData, 'taskCount' => $taskCount['count'], 'userCount' => $userCount['count']];
}