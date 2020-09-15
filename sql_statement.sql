DROP DATABASE IF EXISTS project1;
CREATE DATABASE project1;

CREATE TABLE account(
  id INT NOT NULL,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(100),
  PRIMARY KEY(id)
);

CREATE TABLE (
  id INT NOT NULL,
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