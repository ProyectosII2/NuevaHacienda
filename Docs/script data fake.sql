insert into resident (id_resident, resident_code, first_name, last_name, email, phone, created_at, updated_at) values (1,88,'Byron Rene','Abadio','abadiobyre@gmail.com',40613787,'2017-11-15','2017-11-15');
insert into resident (id_resident, resident_code, first_name, last_name, email, phone, created_at, updated_at) values (2,89,'Luis Eduardo','Acajabon Torres','luis.acajabon@hotmail.com',59661322,'2017-11-15','2017-11-15');
insert into resident (id_resident, resident_code, first_name, last_name, email, phone, created_at, updated_at) values (3,90,'María','García','mariagarcia@gmail.com',54128965,'2017-11-15','2017-11-15');
insert into resident (id_resident, resident_code, first_name, last_name, email, phone, created_at, updated_at) values (4,91,'Juan Jose','Perez','perez15@hotmail.com',41596385,'2017-11-15','2017-11-15');
insert into resident (id_resident, resident_code, first_name, last_name, email, phone, created_at, updated_at) values (5,92,'Ramon','Diaz','diaz.ramon63@yahoo.es',51529874,'2017-11-15','2017-11-15');

insert into residence (id_residence, residence_code, telephone, address, sector) values (1,88,40613787,'36 CALLE A 26-60',2);
insert into residence (id_residence, residence_code, telephone, address, sector) values (2,89,59661322,'28 AVENIDA A 28-30',4);
insert into residence (id_residence, residence_code, telephone, address, sector) values (3,90,54128965,'27 AVENIDA A 27-25',4);
insert into residence (id_residence, residence_code, telephone, address, sector) values (4,91,41596385,'36 CALLE A 26-56',2);
insert into residence (id_residence, residence_code, telephone, address, sector) values (5,92,51529874,'35 CALLE A 26-16',2);

insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (1,1,3030,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (2,1,3040,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (3,2,3050,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (4,2,3060,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (5,3,3070,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (6,3,3080,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (7,4,3090,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (8,4,4000,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (9,5,4010,true);
insert into service_card (id_service_card, id_residence, service_card_code, isActive) values (10,5,4020,true);

insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (1,1,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (2,1,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (3,2,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (4,2,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (5,3,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (6,3,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (7,4,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (8,4,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (9,5,'2017-11-15',200.00,'pago de tarjeta');
insert into monthly_bill (id_monthly_bill,id_residence,date,total,description) values (10,5,'2017-11-15',200.00,'pago de tarjeta');