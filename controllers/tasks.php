<?php
function pageTasksController() {
    if (isset($_GET['orderParam'])) {
        $orderParam = explode(':', $_GET['orderParam']);
        if ($orderParam[0] != "created_at" && $orderParam[0] != "name") {
            $_SESSION['orderField'] = "created_at";
            $_SESSION['orderType'] = "asc";
        } else {
            $_SESSION['orderField'] = $orderParam[0] ?? 'created_at';
            $_SESSION['orderType'] = $orderParam[1] ?? 'desc';
        }
    }
    $orderField = $_SESSION['orderField'] ?? 'created_at';
    $orderType = $_SESSION['orderType'] ?? 'desc';
    return ['items' => getTasks($orderField, $orderType), 'orderParam' => $orderField . ":" . $orderType, 'statuses' => pageTaskStatusesById()];
}