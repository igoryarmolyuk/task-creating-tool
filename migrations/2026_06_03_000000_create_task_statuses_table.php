<?php
migrate("create table task_statuses
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);");
migrate("INSERT INTO task_statuses (id, name) VALUES (1, 'TODO');");
migrate("INSERT INTO task_statuses (id, name) VALUES (2, 'In progress');");
migrate("INSERT INTO task_statuses (id, name) VALUES (3, 'Completed');");
migrate("INSERT INTO task_statuses (id, name) VALUES (4, 'On halt');");