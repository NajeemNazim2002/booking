-- Create centre_managers table
CREATE TABLE IF NOT EXISTS `centre_managers` (
  `manager_id` INT PRIMARY KEY AUTO_INCREMENT,
  `centre_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`centre_id`) REFERENCES `centres`(`centre_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

-- Create centres table if it doesn't exist
CREATE TABLE IF NOT EXISTS `centres` (
  `centre_id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `location` VARCHAR(255),
  `description` TEXT,
  `game_types` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bookings table if it doesn't exist
CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` INT PRIMARY KEY AUTO_INCREMENT,
  `centre_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `booking_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `status` ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`centre_id`) REFERENCES `centres`(`centre_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `role` ENUM('admin', 'manager', 'trainer', 'user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for testing
INSERT IGNORE INTO `users` (`username`, `password`, `email`, `role`) 
VALUES ('manager1', '$2y$10$abcdefghijklmnopqrstuv', 'manager1@example.com', 'manager');

INSERT IGNORE INTO `centres` (`name`, `location`, `description`, `game_types`) 
VALUES ('Sports Complex A', '123 Main St', 'Modern sports facility', '["1","2","3"]');

INSERT IGNORE INTO `centre_managers` (`centre_id`, `user_id`) 
SELECT 1, user_id FROM users WHERE username = 'manager1' LIMIT 1;
