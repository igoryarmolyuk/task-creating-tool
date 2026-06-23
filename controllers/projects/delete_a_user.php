<?php
function pageDeleteAUserController() {
    $user_id = $_GET['user_id'];
    $project_id = $_GET['project_id'];
    if ($user_id == user('id')) {
        echo '<script language="javascript">
                  alert("You can\'t delete yourself");
                  location.href="/projects/users_in_project?id=" +' . $project_id . ';
              </script>';
    } else {
        deleteUserFromProject($user_id, $project_id);
        header('Location: /projects/users_in_project?id=' . $project_id);exit;
    }
}