<?php
migrate("
alter table tasks
    add project_id int not null;");
migrate("alter table tasks
    add constraint tasks_projects_id_fk
        foreign key (project_id) references projects (id);");