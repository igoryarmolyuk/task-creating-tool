<?php
migrate("
create table project_users
(
    id         int auto_increment
        primary key,
    user_id    int not null,
    project_id int not null,
    constraint project_users_projects_id_fk
        foreign key (project_id) references projects (id),
    constraint project_users_users_id_fk
        foreign key (user_id) references users (id)
);");