<?php


addRoute('404', function () {
    return [];
}, null, '/templates/empty.htm');

addRoute('tasks', null, null, null, ['auth']);
addRoute('tasks/preview', null, null, null, ['auth']);
addRoute('tasks/create', null, null, null, ['auth']);
addRoute('tasks/edit', null, null, null, ['auth']);
addRoute('tasks/delete', null, null, null, ['auth']);
addRoute('login', null, null, '/templates/auth.htm');
addRoute('registration', null, null, '/templates/auth.htm');