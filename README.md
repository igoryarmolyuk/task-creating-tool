# task-creating-tool
### To start working with the project you need to start Docker:
```docker compose up -d```
### Then you have to run file called migrations.php with this command:
```docker compose exec php php migrations.php```\
NOTE: since the program is in the early development for migrations.php to work you'll have to drop all of your tables and then start migrations.php
### now you have to connect to the MySQL database:
- The default values are:
    - For port is: 3361
    - For user is: dev
    - And For users password is: qwerty
    - For database is: dev

and you should good to go.
### The website location is: ```localhost:8010```
