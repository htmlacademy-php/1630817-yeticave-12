Use yeticave;
INSERT INTO users (login, email, password, contacts)
VALUES ('Лариса', 'mansik18@mail.ru', '123', '870091u29312');
INSERT INTO users (login, email, password, contacts)
VALUES ('Владик', 'vlad@gmail.com', '124', '870091u29312');
INSERT INTO users (login, email, password, contacts)
VALUES ('Виктор', 'Vitek@gmail.com', '125', '870091u29312');

INSERT INTO categories (title,translation)
VALUES ('boards','Доски и лыжи');
INSERT INTO categories (title,translation)
VALUES ('attachment','Крепления');
INSERT INTO categories (title,translation)
VALUES ('boots','Ботинки');
INSERT INTO categories (title,translation)
VALUES ('clothing','Одежда');
INSERT INTO categories (title,translation)
VALUES ('tools','Инструменты');
INSERT INTO categories (title,translation)
VALUES ('other', 'Разное');

INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (1, '2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', '2021-01-29 16:16');
INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (2, 'DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', '2021-01-29 11:22');
INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (3, 'Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', '2021-01-28 19:40');
INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (1, 'Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', '2021-01-29 21:21');
INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (2, 'Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', '2021-01-29 23:23');
INSERT INTO lots (author_id, title, category_id, first_price, image,end_date)
VALUES (3, 'Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', '2020-01-29 23:44');


INSERT INTO bets (bet_sum, bet_author, lot_id)
VALUES (200, 1, 2);
INSERT INTO bets (bet_sum, bet_author, lot_id)
VALUES (220, 2, 2);
INSERT INTO bets (bet_sum, bet_author, lot_id)
VALUES (222, 1, 2);



-- получить список всех категорий
SELECT title FROM categories;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории;
SELECT   b.lot_id, l.title,l.first_price,l.image,b.bet_sum AS current_price, c.title FROM lots AS l  JOIN bets AS b ON b.lot_id = l.id JOIN categories AS c ON c.id = l.category_id WHERE b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id);

-- показать лот по его id. Получите также название категории, к которой принадлежит лот;
SELECT l.*,c.title AS category FROM lots AS l JOIN categories AS c ON c.id = l.category_id WHERE l.id = 2;

-- обновить название лота по его идентификатору;
UPDATE lots
SET title ='DC Ply Mens 2016/2019 Snowboard'
WHERE id = 2;

-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT * FROM bets WHERE lot_id = 2 ORDER BY creation_date;