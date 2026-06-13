<?php
function pageTasksInProjectController()
{
    $project_id = (int)$_GET['id'];
    $tasks = getTasksFromProject($project_id);
    $statuses = pageTaskStatusesById();
    return ['tasks' => $tasks, 'statuses' => $statuses];
}