 /* SQL file for INFO I308 PROJECT Step 3: Building and Populating the Database. */

/* Drop tables if they exist. Add more DROP TABLE statements as you create more tables*/
DROP TABLE IF EXISTS emp_email;
DROP TABLE IF EXISTS customer_email;
DROP TABLE IF EXISTS emp_phone;
DROP TABLE IF EXISTS customer_phone;
DROP TABLE IF EXISTS supplier_phone;
DROP TABLE IF EXISTS resources_allocated;
DROP TABLE IF EXISTS employee;
DROP TABLE IF EXISTS prob_sheet;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS order_details;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS supplier;

/* Create all tables. Order matters due to foreign keys. */

CREATE TABLE supplier
(
id INT AUTO_INCREMENT NOT NULL,
name VARCHAR(50),
PRIMARY KEY (id)
)
ENGINE = innodb;


CREATE TABLE orders 
(
id INT AUTO_INCREMENT NOT NULL,
date DATE,
notes VARCHAR(150),
supplier_id INT,
PRIMARY KEY (id),
FOREIGN KEY (supplier_id) REFERENCES supplier(id)
)
ENGINE = innodb;


CREATE TABLE cart
(
id INT AUTO_INCREMENT NOT NULL,
date DATE,
PRIMARY KEY (id)
)
ENGINE = innodb;

CREATE TABLE customers
(
id INT AUTO_INCREMENT NOT NULL,
fname VARCHAR(25),
lname VARCHAR(25), 
PRIMARY KEY (id)
)
ENGINE = innodb;

CREATE TABLE inventory
(
id INT AUTO_INCREMENT NOT NULL,
quantity INT,
sale_price DECIMAL(10, 2),
purchase_cost DECIMAL(10, 2),
description VARCHAR(225),
cust_id INT,
PRIMARY KEY (id),
FOREIGN KEY (cust_id) REFERENCES customers(id)
)
ENGINE = innodb;

CREATE TABLE order_details
(
quantity INT NOT NULL,
supplier_id INT NOT NULL,
inventory_id INT NOT NULL,
cart_id INT,
FOREIGN KEY (supplier_id) REFERENCES supplier(id),
FOREIGN KEY (inventory_id) REFERENCES inventory(id),
FOREIGN KEY (cart_id) REFERENCES cart(id)
)
ENGINE = innodb;

CREATE TABLE item
(
id INT AUTO_INCREMENT NOT NULL,
name VARCHAR(30),
problem VARCHAR(225),
description VARCHAR(225),
PRIMARY KEY (id)
)
ENGINE = innodb;

CREATE TABLE prob_sheet
(
id INT AUTO_INCREMENT NOT NULL,
estimate DECIMAL(10, 2),
expected_completion DATE,
completion_date DATE,
cust_id INT,
item_id INT,
PRIMARY KEY (id),
FOREIGN KEY (cust_id) REFERENCES customers(id),
FOREIGN KEY (item_id) REFERENCES item(id)
)
ENGINE = innodb;

CREATE TABLE employee
(
id INT AUTO_INCREMENT NOT NULL,
fname VARCHAR(25),
lname VARCHAR(25),
dob DATE,
hourly_rate INT,
PRIMARY KEY (id)
)
ENGINE = innodb;


CREATE TABLE resources_allocated
(
emp_id INT NOT NULL,
prob_sheet_id INT NOT NULL,
inventory_id INT NOT NULL,
man_hours INT,
num_parts_used INT,
FOREIGN KEY (emp_id) REFERENCES employee(id),
FOREIGN KEY (prob_sheet_id) REFERENCES prob_sheet(id),
FOREIGN KEY (inventory_id) REFERENCES inventory(id)
)
ENGINE = innodb;


CREATE TABLE supplier_phone
(
supplier_id INT NOT NULL,
phone VARCHAR(15),
FOREIGN KEY (supplier_id) REFERENCES supplier(id)
)
ENGINE = innodb;

CREATE TABLE customer_phone
(
cust_id INT NOT NULL,
phone VARCHAR(15),
FOREIGN KEY (cust_id) REFERENCES customers(id)
)
ENGINE = innodb;

CREATE TABLE emp_phone
(
emp_id INT NOT NULL,
phone VARCHAR(15),
FOREIGN KEY (emp_id) REFERENCES employee(id)
)
ENGINE = innodb;

CREATE TABLE customer_email
(
cust_email INT NOT NULL,
email VARCHAR(50),
FOREIGN KEY (cust_email) REFERENCES customers(id)
)
ENGINE = innodb;

CREATE TABLE emp_email
(
emp_id INT NOT NULL,
email VARCHAR(50),
FOREIGN KEY (emp_id) REFERENCES employee(id)
)
ENGINE = innodb;



INSERT INTO supplier (name) VALUES
('Tech Parts Inc.'),
('Gadget Solutions'),
('Innovative Supplies'),
('Hardware Central'),
('Electronics Hub'),
('Quick Repairs Ltd.'),
('PC Components Co.'),
('Mobile Innovations'),
('Gaming Gear Ltd.'),
('Audio & Visual Tech');

INSERT INTO customers (fname, lname) VALUES
('Alice', 'Smith'),
('Bob', 'Johnson'),
('Charlie', 'Davis'),
('Diana', 'Clark'),
('Evan', 'Wright'),
('Fiona', 'Lopez'),
('George', 'Hill'),
('Hannah', 'Lopez'),
('Ian', 'Lopez'),
('Julia', 'Torres');

INSERT INTO inventory (quantity, sale_price, purchase_cost, description, cust_id) VALUES
(10, 29.99, 15.00, 'USB-C Cable - 2m', 1),
(15, 59.99, 30.00, 'Wireless Mouse', 2),
(20, 120.00, 80.00, 'Bluetooth Keyboard', 3),
(5, 300.00, 200.00, '24-inch LED Monitor', 4),
(8, 1000.00, 700.00, 'Gaming Laptop', 5),
(12, 50.00, 25.00, 'Portable Hard Drive - 1TB', 6),
(7, 150.00, 95.00, 'Webcam HD', 7),
(30, 12.99, 6.50, 'Ethernet Cable - 5m', 8),
(25, 250.00, 150.00, 'Wireless Headphones', 9),
(10, 350.00, 220.00, 'Smart Watch', 10);

INSERT INTO employee (fname, lname, dob, hourly_rate) VALUES
('James', 'Miller', '1985-07-22', 30),
('Laura', 'Green', '1990-05-14', 28),
('Brian', 'Walker', '1978-09-02', 32),
('Sarah', 'Allen', '1988-11-23', 27),
('Kevin', 'Wright', '1992-04-17', 25),
('Olivia', 'Harris', '1986-12-30', 29),
('Henry', 'Martin', '1975-03-15', 35),
('Emily', 'Young', '1993-08-09', 26),
('Arthur', 'King', '1980-02-25', 31),
('Sophie', 'Adams', '1991-07-01', 24);

INSERT INTO orders (date, notes, supplier_id) VALUES
('2024-03-01', 'Urgent delivery needed for components.', 1),
('2024-03-03', 'Regular monthly restock.', 2),
('2024-03-05', 'Special order for bulk cables.', 3),
('2024-03-07', 'Order for new inventory stock.', 4),
('2024-03-09', 'Express delivery request.', 5),
('2024-03-11', 'Discount applied for bulk order.', 6),
('2024-03-13', 'New supplier first order.', 7),
('2024-03-15', 'Recurring order for office supplies.', 8),
('2024-03-17', 'Urgent replacement parts needed.', 9),
('2024-03-19', 'Special order on customer request.', 10);

INSERT INTO cart (date) VALUES
('2024-03-20'),
('2024-03-21'),
('2024-03-22'),
('2024-03-23'),
('2024-03-24'),
('2024-03-25'),
('2024-03-26'),
('2024-03-27'),
('2024-03-28'),
('2024-03-29');

INSERT INTO order_details (quantity, supplier_id, inventory_id, cart_id) VALUES
(2, 1, 1, 1),
(3, 2, 3, 2),
(1, 3, 5, 3),
(4, 4, 2, 4),
(5, 5, 4, 5),
(2, 6, 6, 6),
(3, 7, 7, 7),
(1, 8, 8, 8),
(2, 9, 9, 9),
(4, 10, 10, 10);

INSERT INTO item (name, problem, description)
VALUES
('Laptop', 'Screen not turning on', 'Apple MacBook Pro Laptop'),
('Phone', 'Battery draining quickly', 'Samsung Galaxy Smartphone'),
('Tablet', 'Touchscreen not responsive', 'Apple iPad Tablet'),
('Gaming Console', 'Overheating and shutting down', 'Sony PlayStation 4 Gaming Console'),
('Printer', 'Paper jam', 'HP OfficeJet Printer'),
('Router', 'Spotty Wi-Fi connection', 'Linksys Router'),
('Watch', 'Display not lighting up', 'Apple Watch'),
('Gaming Console', 'Not Turning On', 'Sony PlayStation 5 Gaming Console'),
('TV', 'No sound', 'Samsung Smart TV'),
('Gaming Console',  'Not reading discs', 'Xbox Series X Gaming Console');

INSERT INTO prob_sheet (estimate, expected_completion, completion_date, cust_id, item_id) VALUES
(200.00, '2024-03-30', NULL, 1, 5),
(150.00, '2024-04-05', NULL, 2, 4),
(100.00, '2024-04-01', '2024-04-03', 3, 3),
(250.00, '2024-04-10', NULL, 4, 2),
(300.00, '2024-04-15', NULL, 5, 1),
(120.00, '2024-03-31', '2024-04-02', 6, 6),
(180.00, '2024-04-07', NULL, 7, 7),
(220.00, '2024-04-09', NULL, 8, 8),
(130.00, '2024-04-12', NULL, 9, 9),
(170.00, '2024-04-14', NULL, 10, 10);

INSERT INTO resources_allocated (emp_id, prob_sheet_id, inventory_id, man_hours, num_parts_used) VALUES
(1, 1, 1, 3, 2),
(2, 2, 2, 4, 1),
(3, 3, 3, 2, 3),
(4, 4, 4, 5, 2),
(5, 5, 5, 6, 1),
(6, 6, 6, 3, 2),
(7, 7, 7, 2.5, 3),
(8, 8, 8, 4, 1),
(9, 9, 9, 3.5, 2),
(10, 10, 10, 4.5, 1);

INSERT INTO customer_phone (cust_id, phone) VALUES
(1, '555-1001'),
(2, '555-1002'),
(3, '555-1003'),
(4, '555-1004'),
(5, '555-1005'),
(6, '555-1006'),
(7, '555-1007'),
(8, '555-1008'),
(9, '555-1009'),
(10, '555-1010');

INSERT INTO customer_email (cust_email, email) VALUES
(1, 'alice.smith@email.com'),
(2, 'bob.johnson@email.com'),
(3, 'charlie.davis@email.com'),
(4, 'diana.clark@email.com'),
(5, 'evan.wright@email.com'),
(6, 'fiona.lopez@email.com'),
(7, 'george.hill@email.com'),
(8, 'hannah.lee@email.com'),
(9, 'ian.scott@email.com'),
(10, 'julia.torres@email.com');

INSERT INTO emp_phone (emp_id, phone) VALUES
(1, '555-2001'),
(2, '555-2002'),
(3, '555-2003'),
(4, '555-2004'),
(5, '555-2005'),
(6, '555-2006'),
(7, '555-2007'),
(8, '555-2008'),
(9, '555-2009'),
(10, '555-2010');

INSERT INTO emp_email (emp_id, email) VALUES
(1, 'james.miller@company.com'),
(2, 'laura.green@company.com'),
(3, 'brian.walker@company.com'),
(4, 'sarah.allen@company.com'),
(5, 'kevin.wright@company.com'),
(6, 'olivia.harris@company.com'),
(7, 'henry.martin@company.com'),
(8, 'emily.young@company.com'),
(9, 'arthur.king@company.com'),
(10, 'sophie.adams@company.com');

