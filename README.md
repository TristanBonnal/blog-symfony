# Mon super blob :newspaper: :pencil2: :camera:

## The website :computer:

I made this blog while I was learning Symfony with my school. I wanted to practice CRUD, Symfony CLI mainly and security.
Working features: 
-CRUD
-Authentication/authorizations
-Roles hierarchy
-Filters (by author or category)
-Search bar
-Pagination


![image info](./blogSC.png)

![image info](./blogSC2.png)

## Stack :wrench:

- Symfony 5.4
- Twig
- Bootstrap
- KnpPaginatorBundle
- Faker

## How to install project :hammer:

- After cloning the repo, run :
  
    ```bash
    composer install
    ```

- Specify your work environment :

    ```bash
    DATABASE_URL="mysql://user_name:password@127.0.0.1:3306/database_name?serverVersion=mariadb-10.3.25"
    APP_ENV=dev
    ```

- Load migragtions and fixtures :

    ```bash
    php bin/console d:d:c
    php bin/console d:m:m
    php bin/console d:f:l

    ```

- If needed, clear cache :

    ```bash
    php bin/console cache:clear
    ```
