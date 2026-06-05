<?php
migrate("
create table tasks
(
    id          int auto_increment
        primary key,
    status_id   int                                 not null,
    name        varchar(255)                        not null,
    description text                                null,
    created_at  timestamp default CURRENT_TIMESTAMP null,
    project_id  int                                 not null,
    constraint tasks_projects_id_fk
        foreign key (project_id) references projects (id),
    constraint tasks_to_task_statuses
        foreign key (status_id) references task_statuses (id)
);");