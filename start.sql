CREATE DATABASE image_management;
USE image_management;

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    position VARCHAR(255),
    image_url VARCHAR(255),
    frame_url VARCHAR(255),
    x_pos INT DEFAULT 0,
    y_pos INT DEFAULT 0
);

CREATE TABLE area (
    id INT AUTO_INCREMENT PRIMARY KEY,
    background_url VARCHAR(255)
);
