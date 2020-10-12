CREATE DATABASE bieb;

CREATE TABLE users(
	id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE,
    created_at DATE,
    updated_at DATE,
    PRIMARY KEY (id)
);

CREATE TABLE authors(
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    created_at DATE,
    updated_at DATE,
    PRIMARY KEY (id)
    
);

CREATE TABLE books(
	id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(255),
    author_id INT,
    publishing_year INT,
    genre VARCHAR(255),
    created_at DATE,
    updated_at DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (author_id) REFERENCES authors(id)
);

CREATE TABLE favourites(
	id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    book_id INT,
    created_at DATE,
    updated_at DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);
