-- Create doctors table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    experience_years INT,
    consultation_fee DECIMAL(10,2),
    profile_image VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert real doctor data
INSERT INTO doctors (name, specialization, email, phone, experience_years, consultation_fee, bio) VALUES
('Dr. Sarah Johnson', 'Cardiology', 'sarah.johnson@hospital.com', '+1-555-0101', 15, 150.00, 'Board-certified cardiologist with expertise in heart disease prevention and treatment.'),
('Dr. Michael Chen', 'Neurology', 'michael.chen@hospital.com', '+1-555-0102', 12, 180.00, 'Specialized in neurological disorders and brain injury rehabilitation.'),
('Dr. Emily Rodriguez', 'Pediatrics', 'emily.rodriguez@hospital.com', '+1-555-0103', 8, 120.00, 'Caring pediatrician focused on child health and development.'),
('Dr. David Thompson', 'Orthopedics', 'david.thompson@hospital.com', '+1-555-0104', 20, 200.00, 'Expert in bone and joint surgery with advanced techniques.'),
('Dr. Lisa Wang', 'Dermatology', 'lisa.wang@hospital.com', '+1-555-0105', 10, 130.00, 'Specialized in skin conditions and cosmetic dermatology.'),
('Dr. James Wilson', 'Internal Medicine', 'james.wilson@hospital.com', '+1-555-0106', 18, 160.00, 'Comprehensive internal medicine specialist for adult health.');

-- Update appointments table to include doctor_id
ALTER TABLE appointments ADD COLUMN doctor_id INT;
ALTER TABLE appointments ADD FOREIGN KEY (doctor_id) REFERENCES doctors(id);

-- Update doctor_availability table to include doctor_id
ALTER TABLE doctor_availability ADD COLUMN doctor_id INT;
ALTER TABLE doctor_availability ADD FOREIGN KEY (doctor_id) REFERENCES doctors(id);
