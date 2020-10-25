DROP DATABASE IF EXISTS project1;
-- create new db
CREATE DATABASE project1;

-- select project 1 as the default database
USE project1;

CREATE TABLE usertype(
    id INT NOT NULL AUTO_INCREMENT,
    type VARCHAR(255),
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY(id)
);

-- statement to create table account
CREATE TABLE account(
    id INT NOT NULL AUTO_INCREMENT,
    type_id INT NOT NULL,
    username VARCHAR(250) UNIQUE,
    email VARCHAR(250) UNIQUE NOT NULL,
    password VARCHAR(250) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(type_id) REFERENCES usertype(id)
);

-- statement to create table persoon
CREATE TABLE person(
    id INT NOT NULL AUTO_INCREMENT,
    account_id INT NOT NULL,
    first_name VARCHAR(250) NOT NULL,
    middle_name VARCHAR(250),
    last_name VARCHAR(250) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(account_id) REFERENCES account(id)
);

-- insert entrIES into table usertype (admin AND user)
INSERT INTO usertype VALUES (NULL, 'admin', now(), now()), (NULL, 'user', now(), now());

-- RUN POPULATE.PHP to fill rest of the tables.