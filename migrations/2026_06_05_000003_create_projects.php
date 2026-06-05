<?php
migrate("
create table projects
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);");