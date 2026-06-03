<?php
migrate("create table tasks
(
    id          int auto_increment
        primary key,
    status_id   int                                 not null,
    name        varchar(255)                        not null,
    description text                                null,
    created_at  timestamp default CURRENT_TIMESTAMP null,
    constraint tasks_to_task_statuses
        foreign key (status_id) references task_statuses (id)
);");