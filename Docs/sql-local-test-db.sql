/*
Para conectarse a postgresql
1. en la carpeta de php, buscar php.ini y en la linea 914 descomentar la linea del driver php_pgsql
2. https://stackoverflow.com/questions/36030961/use-a-postgres-database-with-symfony3 
archivos descomentar lineas necesarias en config.yml, parameter.yml y security.yml
3. comando en carpeta de proyecto para crear bd (y probar conexion) 
php bin/console doctrine:database:create
4. Hacer src/AppBundle/Entity/User.php
5. autogenerar tabla usuarios (app_users)
php bin/console doctrine:schema:update --force --verbose

Comando para borrar bdd y volverla a hacer

cls && echo Creando bdd && php bin/console doctrine:database:create && echo. && echo Creando Schema && php bin/console doctrine:schema:update --force --verbose



/* inserts despues de mapear proyecto en symfony con php
bcrypt hash*/
INSERT INTO public."app_users" ("id","username", "password","role","email","is_active")
VALUES (nextval('user_gen'),'julio', '$2a$12$SgKol5wgx0lKldG8Ek2rKO4ZhydDlMIgrnnaQGT3FXkx4/qm.hd.G', 'ROLE_ADMIN','some@mail.com',true);
INSERT INTO public."resident" ("id_resident", "resident_code", "first_name", "last_name", "email", "phone", "created_at", "updated_at")
VALUES (nextval('resident_gen'),'123456780123', 'ELEMENTO DE', 'PRUEBA', 'algun@mail.com', '55667788', '2006-6-6 00:00:00', '2006-6-6 00:00:00');
INSERT INTO public."residence" ("id_residence", "id_resident", "residence_code", "telephone","address","sector")
VALUES (nextval('residence_gen'), null, '100','88889999','dirección de vivienda','1');
select * from app_users;

	
