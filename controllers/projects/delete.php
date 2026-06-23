<?php
function pageDeleteController() {
    $id = (int)$_GET['id'];

    deleteProject($id);
    header('Location: /');
}