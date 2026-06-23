<?php


addRoute('404', function () {
    return [];
}, null, '/templates/empty.htm');

addRoute('login', null, null, '/templates/auth.htm');
addRoute('registration', null, null, '/templates/auth.htm');

addRoute('projects', null, null, null, ['auth']);
addRoute('projects/create', null, null, null, ['auth']);
addRoute('projects/preview', null, null, null, ['auth']);
addRoute('projects/delete', null, null, null, ['auth']);
addRoute('projects/tasks_in_project', null, null, null, ['auth']);
addRoute('projects/users_in_project', null, null, null, ['auth']);
addRoute('projects/add_a_user', null, null, null, ['auth']);
addRoute('projects/delete_a_user', null, null, null, ['auth']);

addRoute('tasks', null, null, null, ['auth']);
addRoute('tasks/preview', null, null, null, ['auth']);
addRoute('tasks/create', null, null, null, ['auth']);
addRoute('tasks/edit', null, null, null, ['auth']);
addRoute('tasks/delete', null, null, null, ['auth']);