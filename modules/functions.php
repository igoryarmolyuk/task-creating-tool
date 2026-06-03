<?php
function saveTask($name, $description, $status_id, $id = null) {
    global $db_link;

    $name = mysqli_real_escape_string($db_link, $name);
    $description = mysqli_real_escape_string($db_link, $description);
    $status_id = mysqli_real_escape_string($db_link, $status_id);

    if ($id !== null) {
        $id = (int)$id;

        return mysqli_query($db_link, "UPDATE tasks SET name = '$name', description = '$description', status_id = '$status_id' WHERE id = '$id'");
    }

    $created_at = date('Y-m-d H:i:s');
    $result = mysqli_query($db_link, "INSERT INTO tasks (name, description, created_at, status_id) VALUES ('$name', '$description', '$created_at', '$status_id')");
    if (!$result) {
        return false;
    }

    return mysqli_insert_id($db_link);
}

function deleteTask($id) {
    global $db_link;
    return mysqli_query($db_link, "DELETE FROM tasks WHERE id = $id");
}

function getTaskById($id) {
    global $db_link;
    $result = mysqli_query($db_link, "SELECT * FROM tasks WHERE id='$id'");

    return mysqli_fetch_assoc($result);
}
function storeGetJsonTask($id) {
    global $db_link;

    $content = mysqli_query($db_link, "SELECT * FROM `tasks` WHERE `id`='$id'");
    return json_decode($content, true);
}
function render($template, $data = []) {
    ob_start();

    extract($data);
    include $template;
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function route() {
    $currentUri = $_SERVER['REQUEST_URI'];
    $urlParts = parse_url($currentUri);
    $path = trim($urlParts['path'], '/') ?: 'tasks';
    $path_tmp = explode('/', $path);
    $action = end($path_tmp) ?: 'tasks';
    $ROOT_DIR = dirname(__DIR__);
    $action = basename(preg_replace('/[-_]/', ' ', $action));
    $actionName = 'page' . str_replace(' ', '', ucwords($action)) . 'Controller';
    $controller_path = $ROOT_DIR . '/controllers/' . $path . '.php';
    $template = $ROOT_DIR . '/templates/' . $path . '.htm';

    if (!file_exists($controller_path)) {
        $controller_path = $ROOT_DIR . '/controllers/404.php';
        $actionName = 'page404Controller';
    }
    include $controller_path;

    $result = $actionName();
    $content = render($template, $result);

    echo render($ROOT_DIR . '/templates/index.htm', ['content' => $content]);
}

function pageNews(): array {
    return [];
}

function pageNewsCreate() {
    return [];
}

function getTasks($orderField = 'created_at', $orderType = 'desc') {
    global $db_link;
    $items = [];

    $result = mysqli_query($db_link, "SELECT * FROM tasks order by $orderField $orderType");
    while ($row = mysqli_fetch_assoc($result)) {
       $items[] = $row;
    }
    return $items;
}

function getTaskStatuses() {
    global $db_link;
    $items = [];

    $result = mysqli_query($db_link, "SELECT * FROM task_statuses");
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    return $items;
}

function pageTaskStatusesById() {
    $statuses = [];
    foreach (getTaskStatuses() as $taskStatus) {
        $statuses[$taskStatus['id']] = $taskStatus['name'];
    }

    return $statuses;
}