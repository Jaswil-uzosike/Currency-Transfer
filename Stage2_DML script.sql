
INSERT INTO currency(currency_id,currency_name) VALUES (1,'GBP');
INSERT INTO currency(currency_id,currency_name) VALUES (2,'USD');
INSERT INTO currency(currency_id,currency_name) VALUES (3,'EUR');

INSERT INTO admins(admin_id,fname,lname,username,email,passwords) VALUES (1,'Hailee','Bae','Admin@Hailee','haileebae@gmail.com','hailee123');
INSERT INTO admins(admin_id,fname,lname,username,email,passwords) VALUES (2,'Wazza','Roon','Admin@Wazza','wazzaroon@gmail.com','wazza123');

INSERT INTO acctype(type_id,type_name) VALUES (1,'personal');
INSERT INTO acctype(type_id,type_name) VALUES (2,'business');
INSERT INTO acctype(type_id,type_name) VALUES (3,'student');

INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (1,"GBPUSD",1.17);
INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (2,"GBPEUR",1.26);
INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (3,"EURUSD",1.11);
INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (4,"EURGBP",0.86);
INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (5,"USDGBP",0.79);
INSERT INTO exchange_rates(rate_id,exchange_name,exchange_value) VALUES (6,"USDEUR",0.90);