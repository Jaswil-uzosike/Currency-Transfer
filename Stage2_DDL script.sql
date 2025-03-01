CREATE TABLE useracc (
	user_id INT (11) AUTO_INCREMENT PRIMARY KEY,
	fname VARCHAR (25) NOT NULL,
    lname VARCHAR (25) NOT NULL,
    username VARCHAR (25) NOT NULL,
    gender VARCHAR (10) NOT NULL,
    email VARCHAR (30) NOT NULL,
    phone_number INT (25) NOT NULL,
    passwords VARCHAR (25) NOT NULL,
    acc_status VARCHAR (25) DEFAULT NULL
);

CREATE TABLE currency (
	currency_id INT (2) AUTO_INCREMENT PRIMARY KEY,
    currency_name VARCHAR (3) NOT NULL
);

CREATE TABLE bank (
	bank_id INT (11) AUTO_INCREMENT PRIMARY KEY,
	account_no INT (17) NOT NULL,
    acc_name VARCHAR (25) NOT NULL,
	sort_swift_code VARCHAR (25) NOT NULL,
	currency_id INT (2) NOT NULL,
    user_id INT (11) NOT NULL, 
	FOREIGN KEY (user_id) REFERENCES useracc (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (currency_id) REFERENCES currency (currency_id) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE acctype (
	type_id INT (2) AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR (15) NOT NULL
);

CREATE TABLE currencyacc (
	account_id INT (11) AUTO_INCREMENT PRIMARY KEY,
	balance DECIMAL (8, 2) NOT NULL,
    max_income DECIMAL (8, 2) NOT NULL,
    type_id INT (2) NOT NULL,
    user_id INT (11) NOT NULL,
    currency_id INT (2) NOT NULL,
	FOREIGN KEY (type_id) REFERENCES acctype (type_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (user_id) REFERENCES useracc (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (currency_id) REFERENCES currency (currency_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE admins (
	admin_id INT (2) AUTO_INCREMENT PRIMARY KEY,
   	fname VARCHAR (25) NOT NULL,
    lname VARCHAR (25) NOT NULL,
    username VARCHAR (25) NOT NULL,
	email VARCHAR (30) NOT NULL,
	passwords VARCHAR (25) NOT NULL
);

CREATE TABLE transactions (
	transaction_id INT (20) AUTO_INCREMENT PRIMARY KEY,
	amount_sent DECIMAL (8, 2) NOT NULL,
    amount_received DECIMAL (8, 2) NOT NULL,
    senderacc_id INT (11) NOT NULL,
    receiveracc_id INT (11) NOT NULL,
	FOREIGN KEY (senderacc_id) REFERENCES currencyacc (account_id)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (receiveracc_id) REFERENCES currencyacc (account_id) ON DELETE CASCADE ON UPDATE CASCADE,
	trans_date date NOT NULL,
    currency_from_id INT (2) NOT NULL,
    currency_to_id INT (2) NOT NULL,
    trans_state VARCHAR (20) NOT NULL,
	FOREIGN KEY (currency_from_id) REFERENCES currency (currency_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (currency_to_id) REFERENCES currency (currency_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE suspicious (
	sa_id INT (11) AUTO_INCREMENT PRIMARY KEY,
	sa_description VARCHAR (80) NOT NULL,
    transaction_id INT (20) NOT NULL,
    admin_id INT (2) NOT NULL,
	FOREIGN KEY (transaction_id) REFERENCES transactions (transaction_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (admin_id) REFERENCES admins (admin_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE profit (
	profit_id INT (2) AUTO_INCREMENT PRIMARY KEY,
	transaction_id INT (20) NOT NULL,
    trans_profit_GBP DECIMAL (8, 2) NOT NULL,
    trans_date date,
    FOREIGN KEY (transaction_id) REFERENCES transactions (transaction_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE exchange_rates (
	rate_id INT (2) AUTO_INCREMENT PRIMARY KEY,
   	exchange_name VARCHAR (25) NOT NULL,
    exchange_value DECIMAL (8, 2) NOT NULL
);