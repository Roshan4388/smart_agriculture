-- Smart Agriculture database schema
-- Run this SQL to create the application database structure.

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('farmer','admin') NOT NULL DEFAULT 'farmer',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS crops (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  season VARCHAR(100) NOT NULL,
  soil_type VARCHAR(100) NOT NULL,
  expected_yield VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  tips TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  category VARCHAR(120) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  quantity INT NOT NULL,
  description TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  buyer_name VARCHAR(150) NOT NULL,
  product_name VARCHAR(200) NOT NULL,
  quantity INT NOT NULL,
  total_price DECIMAL(12,2) NOT NULL,
  status VARCHAR(80) NOT NULL DEFAULT 'Pending',
  ordered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS consultations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  topic VARCHAR(200) NOT NULL,
  details TEXT NOT NULL,
  contact_info VARCHAR(200) NOT NULL,
  status VARCHAR(80) NOT NULL DEFAULT 'Pending',
  requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS security_alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alert_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  severity ENUM('info','medium','high') NOT NULL,
  message TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS otp_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  phone VARCHAR(30) NOT NULL,
  code VARCHAR(10) NOT NULL,
  expires_at DATETIME NOT NULL,
  verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS field_boundaries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  coordinates JSON NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example admin user
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@smartagri.local', '$2y$10$kQvd0wVNX6wJTLP3rwm/7.ipZnsqD7AIhb3pHvdFDKGtjBuaukf1i', 'admin');

-- Example crop data
INSERT INTO crops (name, season, soil_type, expected_yield, description, tips) VALUES
('Rice', 'Monsoon', 'Clay loam', '4-5 tons/ha', 'Rice thrives in wet seasons with high soil moisture.', 'Maintain water level and check pests after heavy rains.'),
('Maize', 'Autumn', 'Sandy loam', '3-4 tons/ha', 'Maize prefers well-drained soil and even temperature.', 'Apply fertilizer during the vegetative stage.'),
('Tomato', 'Summer', 'Loamy', '20-25 tons/ha', 'Tomatoes need good sunlight and moderate irrigation.', 'Protect from heavy rain and support vines.');

