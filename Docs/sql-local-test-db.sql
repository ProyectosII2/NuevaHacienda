CREATE DATABASE haciendatest
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    CONNECTION LIMIT = -1;

COMMENT ON DATABASE haciendatest
    IS 'Test Local';
	
create table public."user"
(
	"id" Serial primary key,
    "username" Varchar(20) NOT NULL,
    "password" Varchar(50) NOT NULL,
    Constraint Pkey1 Unique ("username")
);
Alter table public."user" OWNER TO postgres;

INSERT INTO public."user" ("username", "password")
VALUES ('julio', '5f4dcc3b5aa765d61d8327deb882cf99');

INSERT INTO public."user" ("username", "password")
VALUES ('test', 'test');

	
