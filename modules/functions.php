<?php
function saveTask($name, $description, $status_id, $project_id, $id = null) {
    global $db_link;

    $name = mysqli_real_escape_string($db_link, $name);
    $description = mysqli_real_escape_string($db_link, $description);
    $status_id = mysqli_real_escape_string($db_link, $status_id);
    $project_id = mysqli_real_escape_string($db_link, $project_id);

    if ($id !== null) {
        $id = (int)$id;

        return mysqli_query($db_link, "UPDATE tasks SET name = '$name', description = '$description', status_id = '$status_id' WHERE id = '$id'");
    }

    $created_at = date('Y-m-d H:i:s');
    $result = mysqli_query($db_link, "INSERT INTO tasks (name, description, created_at, status_id, project_id) VALUES ('$name', '$description', '$created_at', '$status_id', '$project_id')");
    if (!$result) {
        return false;
    }

    return mysqli_insert_id($db_link);
}

function saveProject($name, $id = null) {
    global $db_link;

    $name = mysqli_real_escape_string($db_link, $name);

    if ($id !== null) {
        $id = (int)$id;

        return mysqli_query($db_link, "UPDATE projects SET name = '$name' WHERE id = '$id'");
    }

    $created_at = date('Y-m-d H:i:s');
    $result = mysqli_query($db_link, "INSERT INTO projects (name) VALUES ('$name')");
    if (!$result) {
        return false;
    }

    $project_id = mysqli_insert_id($db_link);
    $user_id = user('id');
    $result = mysqli_query($db_link, "INSERT INTO project_users (user_id, project_id) VALUES ($user_id, $project_id)");

    return $project_id;
}

function sendRegistration($first_name, $last_name, $name, $email, $password) {
    global $db_link;

    $first_name = mysqli_real_escape_string($db_link, $first_name);
    $last_name = mysqli_real_escape_string($db_link, $last_name);
    $username = mysqli_real_escape_string($db_link, $name);
    $email = mysqli_real_escape_string($db_link, $email);
    $password = md5(mysqli_real_escape_string($db_link, $password));
    $created_at = date('Y-m-d H:i:s');
    $activity = 0;

    // Added 'activity' to the column list so it matches the 7 values
    $query = "INSERT INTO users (first_name, last_name, username, email, password, created_at, activity) 
              VALUES ('$first_name', '$last_name', '$username', '$email', '$password', '$created_at', '$activity')";

    $result = mysqli_query($db_link, $query);
    if (!$result) {
        return false;
    }

    return mysqli_insert_id($db_link);
}

function deleteTask($id) {
    global $db_link;
    return mysqli_query($db_link, "DELETE FROM tasks WHERE id = '$id'");
}

function deleteProject($id)
{
    global $db_link;
    mysqli_query($db_link, "DELETE FROM project_users WHERE project_id = '$id'");
    mysqli_query($db_link, "DELETE FROM tasks WHERE project_id = '$id'");
    return mysqli_query($db_link, "DELETE FROM projects WHERE id = '$id'");
}

function getTaskById($id) {
    global $db_link;
    $result = mysqli_query($db_link, "SELECT * FROM tasks WHERE id='$id'");

    return mysqli_fetch_assoc($result);
}

function getTaskCount($project_id) {
    global $db_link;
    $result = mysqli_query($db_link, "SELECT COUNT(*) as count FROM tasks WHERE project_id='$project_id'");
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
    global $routes;
    $currentUri = $_SERVER['REQUEST_URI'];
    $urlParts = parse_url($currentUri);
    $path = trim($urlParts['path'], '/') ?: 'tasks';
    $ROOT_DIR = dirname(__DIR__);

    if (!isset($routes[$path])) {
        $path = '404';
    } else {
        if (!empty($routes[$path]['middlewares'])) {
            foreach ($routes[$path]['middlewares'] as $middleware) {
                callMiddleware($middleware);
            }
        }
    }

    $template = $ROOT_DIR . '/templates/' . $path . '.htm';

    if (empty($routes[$path]['controller'])) {
        $path_tmp = explode('/', $path);
        $action = end($path_tmp) ?: 'tasks';
        $action = basename(preg_replace('/[-_]/', ' ', $action));
        $actionName = 'page' . str_replace(' ', '', ucwords($action)) . 'Controller';
        $controller_path = $ROOT_DIR . '/controllers/' . $path . '.php';

        if (!file_exists($controller_path)) {
            $path = '404';
            $template = $ROOT_DIR . '/templates/' . $path . '.htm';
            $result = $routes[$path]['controller']();
        } else {
            include $controller_path;
            $result = $actionName();
        }

    } else {
        $result = $routes[$path]['controller']();
    }

    $content = render($template, $result);

    $commonTemplate =  $routes[$path]['commonTemplate'] ?? '/templates/index.htm';

    echo render($ROOT_DIR . $commonTemplate, ['content' => $content]);
}

function addRoute($path, callable $controller = null, $template = null, $commonTemplate = null, $middlewares = []) {
    global $routes;
    $routes[$path] = [
        'controller' => $controller,
        'template' => $template,
        'commonTemplate' => $commonTemplate,
        'middlewares' => $middlewares
    ];
}

function addValidator($name, callable $validator) {
    global $validators;
    $validators[$name] = $validator;
}

function validate($value, array $rules) {
    global $validators;

    // If nullable and empty, validation passes immediately
    if (
        isset($rules['nullable']) &&
        $rules['nullable'] &&
        ($value === null || $value === '')
    ) {
        return true;
    }

    foreach ($rules as $rule => $param) {
        if (!isset($validators[$rule])) {
            return false;
        }

        $validator = $validators[$rule];

        if ($param === true) {
            if (!$validator($value)) {
                return false;
            }
        } else {
            if (!$validator($value, $param)) {
                return false;
            }
        }
    }

    return true;
}

function addMiddleware($middleWareName, callable $handler) {
    global $middleWares;
    $middleWares[$middleWareName] = $handler;
}

function callMiddleware($name) {
    global $middleWares;

    if (isset($middleWares[$name])) {
        return $middleWares[$name]();
    }

    return null;
}

function getProjects($orderField = 'created_at', $orderType = 'desc') {
    global $db_link;
    $items = [];

    $user_id = user('id');
    $result = mysqli_query($db_link, "SELECT * FROM projects, project_users where project_users.project_id = projects.id and user_id=$user_id order by $orderField $orderType");
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    return $items;
}

function getProjectById($id) {
    global $db_link;
    $result = mysqli_query($db_link, "SELECT * FROM projects WHERE id='$id'");

    return mysqli_fetch_assoc($result);
}

function getTasksFromProject($project_id) {
    global $db_link;
    $items = [];
    $result = mysqli_query($db_link, "SELECT * FROM tasks WHERE project_id='$project_id'");
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    return $items;
}

function getTasks($orderField = 'created_at', $orderType = 'desc', $project_id = null) {
    global $db_link;
    $items = [];

    $project_sql = $project_id ? "tasks.project_id = $project_id AND " : "";

    $user_id = user('id');
    $result = mysqli_query($db_link, "SELECT tasks.*, projects.name as project_name FROM tasks, projects, project_users where tasks.project_id = projects.id and
                                                                                        projects.id = project_users.project_id and $project_sql
                                                                                        project_users.user_id = $user_id order by $orderField $orderType");
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

function migrate($sql) {
    global $db_link;
    $result = mysqli_query($db_link, $sql);
}

function user($field = null) {
    global $db_link, $user;

    if (empty($user)) {
        $user_id = $_SESSION['user_id'];
        $result = mysqli_query($db_link, "SELECT * FROM users WHERE id = $user_id LIMIT 1");
        $user = mysqli_fetch_assoc($result);
    }
    return $field ? $user[$field] : $user;
}