<?php
function pageAddAUserController() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_POST['user_id'];
        $project_id = $_GET['project_id'];
        addUserToTheProject($project_id, $user_id);
        header('Location: /projects/users_in_project?id='.$project_id);
    }
    return ['users' => getUsersFromProjectForAdd()];
}