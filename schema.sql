CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE users
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email             VARCHAR(128) NOT NULL UNIQUE,
    login             VARCHAR(128) NOT NULL UNIQUE,
    password          CHAR(64)     NOT NULL,
    contacts          VARCHAR(255)

);

CREATE TABLE categories
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    title     VARCHAR(128)
);

CREATE TABLE lots
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title         VARCHAR(128),
    description   VARCHAR(255),
    image         VARCHAR(255),
    first_price   INT,
    end_date      TIMESTAMP,
    bet_step      INT,
    author_id     INT NOT NULL,
    category_id   INT NOT NULL,
    winner_id     INT,

    FOREIGN KEY (author_id) REFERENCES users (id),
    FOREIGN KEY (winner_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE bets
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bet_sum       INT,
    bet_author    INT,
    lot_id        INT,

    FOREIGN KEY (bet_author) REFERENCES users (id),
    FOREIGN KEY (lot_id) REFERENCES lots (id)
);



CREATE INDEX u_mail ON users (email);
CREATE INDEX u_login ON users (login);
CREATE INDEX lots_title ON lots (title);
CREATE INDEX lots_description ON lots (description);
