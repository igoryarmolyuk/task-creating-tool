<?php
migrate("create table users
(
    id         int auto_increment
        primary key,
    first_name varchar(255) not null,
    last_name  varchar(255) not null,
    username   varchar(255) not null,
    email      varchar(255) not null,
    password   int          not null,
    created_at int          not null,
    activity   tinyint(1)   not null,
    constraint users_pk
        unique (username)
);");