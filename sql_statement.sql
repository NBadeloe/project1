-- create table
CREATE DATABASE project1;

CREATE TABLE account(
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(100),
  PRIMARY KEY(id)
);

CREATE TABLE person(
  id INT NOT NULL AUTO_INCREMENT,
  first_name varchar(45),
  insertion varchar(15),
  last_name varchar(45),
  email varchar UNIQUE(100),
  username varchar(30),
  password varchar(100),
  account_id INT,
  PRIMARY KEY id,
  FOREIGN KEY account_id REFRENCES account(account_id)
);

-- add dummy admin account

INSERT INTO account (email, password) 
VALUES ('admin@mail.com', 'password1');

INSERT INTO person (account_id, first_name, last_name, email, password) 
VALUES (1, 'Admin', 'User', 'admin@mail.com', 'password1');

--create table usertype
CREATE TABLE usertype(
    id INT NOT NULL AUTO_INCREMENT,
    type VARCHAR(40),
    created_at DATETIME,
    updated_at DATETIME,
    PRIMARY KEY (id)
    )
-- alter person and account   
ALTER TABLE account
    ADD type INT,
    ADD created_at DATETIME,
    ADD updated_at DATETIME, 
    ADD FOREIGN KEY (type) REFERENCES usertype(id)
    ;
    
    
ALTER TABLE person
	DROP COLUMN email, 
    DROP COLUMN username, 
    DROP COLUMN password,
    ADD created_at DATETIME,
    ADD updated_at DATETIME
    ;
      