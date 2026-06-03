<?php
migrate("create table task_statuses
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);");