CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255),
  user_type ENUM('organizer', 'participant', 'administrator')
);

CREATE TABLE events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(255),
  description TEXT,
  date DATE,
  time TIME,
  location VARCHAR(255),
  category_id INT,
  price DECIMAL(10, 2),
  image VARCHAR(255),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  event_id INT,
  payment_status ENUM('pending', 'completed'),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  event_id INT,
  rating INT,
  comment TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255)
);


INSERT INTO users (name, email, password, user_type)
VALUES ('admin', 'admin@admin.com', 'admin', 'administrator');


INSERT INTO users (name, email, password, user_type)
VALUES ('organizer', 'organizer@organizer.com', 'organizer', 'organizer');

INSERT INTO users (name, email, password, user_type)
VALUES ('participant', 'participant@organizer.com', 'participant', 'participant');

INSERT INTO events (user_id, title, description, date, time, location, category_id, price, image)
VALUES (1, 'Festinha do Unicórnio', 'O local onde unicórnios felizes, podem unicornizar pelo mundo unicornizante.', '2023-02-14', '04:20:00', 'Unicórniolandia', 1, 666.00, 'default.jpg');

INSERT INTO categories (name)
VALUES ('Festa do cocô de sorvete!');