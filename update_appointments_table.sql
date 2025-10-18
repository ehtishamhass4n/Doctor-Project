-- Update appointments table to include patient_phone field
ALTER TABLE appointments ADD COLUMN patient_phone VARCHAR(20);

-- Update existing appointments to have proper structure
-- This ensures compatibility with the new doctor-patient system
