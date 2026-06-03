<?php
//phpinfo();exit;
include '/var/www/html/init.php';

global $db_link;
$tasks = getTasks();
foreach ($tasks as $task) {
    $filepath = task_storage_path . $task;
    $task_info = storeGetJsonTask($filepath);
    print_r($task_info);
    $result = mysqli_query($db_link, "INSERT INTO tasks (name, description, created_at)
VALUES ('{$task_info['title']}', '{$task_info['description']}', '{$task_info['created-at']}')");
//    $row = mysqli_fetch_assoc($result);
//    print_r($row);
}