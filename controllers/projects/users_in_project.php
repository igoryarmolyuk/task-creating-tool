<?php
function pageUsersInProjectController()
{
//    $user_id = user('id');
    $project_id = $_GET['id'];
    $users = getUsersFromProject($project_id);
    return ['project_id' => $project_id, 'users' => $users];
}