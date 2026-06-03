<?php
function pageDeleteController() {
    $id = (int)$_GET['id'];

    deleteTask($id);
    header('Location: /');
}