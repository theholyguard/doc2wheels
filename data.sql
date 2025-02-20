DROP TABLE IF EXISTS technician_services;
DROP TABLE IF EXISTS repairs;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vehicle_categories;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role VARCHAR(50) DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE technician_services (
    id SERIAL PRIMARY KEY,
    technician_id INT REFERENCES users(id) ON DELETE CASCADE,
    service_id INT REFERENCES services(id) ON DELETE CASCADE
);


CREATE TABLE vehicle_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE repairs (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    type_service VARCHAR(255) NOT NULL,
    address_id INT REFERENCES addresses(id) ON DELETE CASCADE,
    technician_id INT REFERENCES users(id) ON DELETE CASCADE,
    vehicle_category_id INT REFERENCES vehicle_categories(id) ON DELETE CASCADE,
    price DECIMAL(10, 2) NOT NULL,
    message TEXT,
    status VARCHAR(50) DEFAULT 'en attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



INSERT INTO services (category, price) VALUES
    ('Dépannage', 100.00),
    ('Réparation', 70.00),
    ('Entretien', 40.00);

INSERT INTO vehicle_categories (name, price) VALUES
    ('Sportive', 50.00),
    ('Roadster', 40.00),
    ('Scooter', 30.00),
    ('Trail', 20.00);

INSERT INTO users (name, email, password, role) VALUES
    ('Noan', 'a@b.c', 'test1234', 'technician');