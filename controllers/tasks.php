<?php
function pageTasksController()
{
    if (isset($_GET['taskOrderParam'])) {
        $taskOrderParam = explode(':', $_GET['taskOrderParam']);
        if ($taskOrderParam[0] != "created_at" && $taskOrderParam[0] != "name") {
            $_SESSION['orderField'] = "created_at";
            $_SESSION['orderType'] = "asc";
        } else {
            $_SESSION['orderField'] = $taskOrderParam[0] ?? 'created_at';
            $_SESSION['orderType'] = $taskOrderParam[1] ?? 'desc';
        }
    }
    $orderField = $_SESSION['orderField'] ?? 'created_at';
    $orderType = $_SESSION['orderType'] ?? 'desc';
    $project_id = $_GET['project_id'] ?? null;
    return ['items' => getTasks($orderField, $orderType, $project_id), 'taskOrderParam' => $orderField . ":" . $orderType, 'projectOrderParam' => $orderField . ":" . $orderType, 'statuses' => pageTaskStatusesById(), 'projects' => getProjects(), 'project_id' => $project_id];
}