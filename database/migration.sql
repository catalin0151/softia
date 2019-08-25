use softia;

CREATE TABLE coffee_machine(
id int auto_increment,
locked_until timestamp,
PRIMARY KEY(id)
) ENGINE = InnoDB;

CREATE TABLE products(
id int auto_increment,
name varchar(100) NOT NULL,
price int NOT NULL,
content varchar(100) NOT NULL,
machine_id int NOT NULL,
quantity int NOT NULL DEFAULT 0,
PRIMARY KEY(id),
FOREIGN KEY(machine_id) REFERENCES coffee_machine(id)
) ENGINE = InnoDB;


CREATE TABLE orders(
id int auto_increment,
machine_id int NOT NULL,
product_id int NOT NULL,
quantity int NOT NULL,
total int NOT NULL,
timestamp timestamp NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(machine_id) REFERENCES coffee_machine(id),
FOREIGN KEY(product_id) REFERENCES products(id)
) ENGINE = InnoDB;

INSERT INTO `coffee_machine` (`id`, `locked_until`) VALUES (1, NULL);
INSERT INTO `products` (`id`, `name`, `price`, `quantity`,`content`, `machine_id`) VALUES (1, 'cappucino', 2, 100 ,'cappucino', 1);
INSERT INTO `products` (`id`, `name`, `price`, `quantity`,`content`, `machine_id`) VALUES (2, 'espresso', 4, 100, 'espresso', 1);
INSERT INTO `products` (`id`, `name`, `price`, `quantity`,`content`, `machine_id`) VALUES (3, 'black coffee', 3, 100,'black coffee', 1);