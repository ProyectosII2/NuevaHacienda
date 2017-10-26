/*
Para conectarse a postgresql
1. en la carpeta de php, buscar php.ini y en la linea 914 descomentar la linea del driver php_pgsql
2. https://stackoverflow.com/questions/36030961/use-a-postgres-database-with-symfony3 
archivos descomentar lineas necesarias en config.yml, parameter.yml y security.yml
3. comando en carpeta de proyecto para crear bd (y probar conexion) 
php bin/console doctrine:database:create
4. Hacer src/AppBundle/Entity/User.php
5. autogenerar tabla usuarios (app_users)
php bin/console doctrine:schema:update --force

CREATE DATABASE haciendatest
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    CONNECTION LIMIT = -1;

COMMENT ON DATABASE haciendatest
    IS 'Test Local';
*

create table public."app_users"
(
	"id" Serial primary key,
    "username" Varchar(20) NOT NULL,
    "password" Varchar(50) NOT NULL,
    "rol" varchar(20) NOT NULL,
    "email" varchar(20) NOT NULL,
    "isActive" integer NOT NULL DEFAULT 1,
    Constraint Ukey1 Unique ("username"),
    Constraint Ukey2 Unique ("mail")
);

Alter table public."app_users" OWNER TO postgres;
*/


/* inserts despues de mapear proyecto en symfony con php
md5 hash*/
INSERT INTO public."app_users" ("id","username", "password","role","email","is_active")
VALUES (nextval('user_gen'),'julio', 'asdf', 'ROLE_ADMIN','some@mail.com',true);
INSERT INTO public."app_users" ("id","username", "password","role","email","is_active")
VALUES (nextval('user_gen'),'roberto', '912EC803B2CE49E4A541068D495AB570', 'ROLE_USER','algun@mail.com',true);
INSERT INTO public."app_users" ("id","username", "password","role","email","is_active")
VALUES (nextval('user_gen'),'barrios', '912EC803B2CE49E4A541068D495AB570', 'ROLE_ADMIN','random@mail.com',false);
INSERT INTO public."app_users" ("id","username", "password","role","email","is_active")
VALUES (nextval('user_gen'),'xep', '912EC803B2CE49E4A541068D495AB570', 'ROLE_USER','whos@mail.com',false);

select * from public."app_users"

	
