-- First, clear existing skills for a cleaner implementation
TRUNCATE TABLE skills;

-- Assign specific skills to each student that match job requirements
-- This ensures students will see recommended and eligible jobs

-- Student 1 - Full Stack Developer Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(1, 'JavaScript', 'Programming Languages'),
(1, 'React', 'Frameworks'),
(1, 'Node.js', 'Frameworks'),
(1, 'MongoDB', 'Databases'),
(1, 'Express', 'Frameworks'),
(1, 'RESTful APIs', 'Tools'),
(1, 'HTML', 'Programming Languages'),
(1, 'CSS', 'Programming Languages');

-- Student 2 - Mobile Developer Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(2, 'Flutter', 'Frameworks'),
(2, 'React Native', 'Frameworks'),
(2, 'Swift', 'Programming Languages'),
(2, 'Kotlin', 'Programming Languages'),
(2, 'Firebase', 'Databases'),
(2, 'Git', 'Tools'),
(2, 'JavaScript', 'Programming Languages');

-- Student 3 - Data Science Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(3, 'Python', 'Programming Languages'),
(3, 'TensorFlow', 'Frameworks'),
(3, 'PyTorch', 'Frameworks'),
(3, 'Data Analysis', 'Tools'),
(3, 'ML Algorithms', 'Tools'),
(3, 'SQL', 'Databases'),
(3, 'Statistics', 'Tools'),
(3, 'R', 'Programming Languages'),
(3, 'Data Visualization', 'Tools');

-- Student 4 - DevOps Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(4, 'Docker', 'Tools'),
(4, 'Kubernetes', 'Tools'),
(4, 'Jenkins', 'Tools'),
(4, 'AWS', 'Tools'),
(4, 'Linux', 'Tools'),
(4, 'Git', 'Tools'),
(4, 'CI/CD', 'Tools');

-- Student 5 - Frontend Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(5, 'JavaScript', 'Programming Languages'),
(5, 'React', 'Frameworks'),
(5, 'Redux', 'Frameworks'),
(5, 'HTML', 'Programming Languages'),
(5, 'CSS', 'Programming Languages'),
(5, 'Responsive Design', 'Tools'),
(5, 'TypeScript', 'Programming Languages');

-- Student 6 - Backend Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(6, 'Java', 'Programming Languages'),
(6, 'Spring Boot', 'Frameworks'),
(6, 'Microservices', 'Tools'),
(6, 'MySQL', 'Databases'),
(6, 'API Design', 'Tools'),
(6, 'SQL', 'Databases');

-- Student 7 - Cybersecurity Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(7, 'Network Security', 'Tools'),
(7, 'Penetration Testing', 'Tools'),
(7, 'SIEM', 'Tools'),
(7, 'Ethical Hacking', 'Tools'),
(7, 'Firewalls', 'Tools'),
(7, 'VPN', 'Tools'),
(7, 'IDS/IPS', 'Tools');

-- Student 8 - UX/UI Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(8, 'UI Design', 'Tools'),
(8, 'UX Research', 'Tools'),
(8, 'Figma', 'Tools'),
(8, 'Adobe XD', 'Tools'),
(8, 'Prototyping', 'Tools'),
(8, 'CSS', 'Programming Languages'),
(8, 'HTML', 'Programming Languages');

-- Student 9 - Cloud Engineering Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(9, 'AWS', 'Tools'),
(9, 'Azure', 'Tools'),
(9, 'Docker', 'Tools'),
(9, 'Kubernetes', 'Tools'),
(9, 'Terraform', 'Tools'),
(9, 'Cloud Integration', 'Tools');

-- Student 10 - Blockchain Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(10, 'Blockchain', 'Tools'),
(10, 'Solidity', 'Programming Languages'),
(10, 'Ethereum', 'Tools'),
(10, 'Smart Contracts', 'Tools'),
(10, 'JavaScript', 'Programming Languages');

-- Student 11 - Mixed skills with Mobile focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(11, 'JavaScript', 'Programming Languages'),
(11, 'React Native', 'Frameworks'),
(11, 'Redux', 'Frameworks'),
(11, 'TypeScript', 'Programming Languages'),
(11, 'Firebase', 'Databases'),
(11, 'Git', 'Tools');

-- Student 12 - Mixed skills with AI focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(12, 'Python', 'Programming Languages'),
(12, 'Deep Learning', 'Tools'),
(12, 'Neural Networks', 'Tools'),
(12, 'Research', 'Tools'),
(12, 'NLP', 'Tools');

-- Student 13 - Game development Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(13, 'Unity', 'Tools'),
(13, 'C#', 'Programming Languages'),
(13, 'Game Design', 'Tools'),
(13, '3D Graphics', 'Tools'),
(13, 'Physics', 'Tools');

-- Student 14 - AR/VR Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(14, 'Unity3D', 'Tools'),
(14, 'C#', 'Programming Languages'),
(14, '3D Modeling', 'Tools'),
(14, 'AR Frameworks', 'Tools'),
(14, 'VR Development', 'Tools');

-- Student 15 - IoT/Embedded Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(15, 'IoT', 'Tools'),
(15, 'Embedded Systems', 'Tools'),
(15, 'C++', 'Programming Languages'),
(15, 'MQTT', 'Tools'),
(15, 'Cloud Integration', 'Tools'),
(15, 'Embedded C', 'Programming Languages'),
(15, 'Microcontrollers', 'Tools'),
(15, 'RTOS', 'Tools'),
(15, 'Electronics', 'Tools');

-- Student 16 - Data Engineering Focus
INSERT INTO skills (user_id, name, skill_type) VALUES
(16, 'Python', 'Programming Languages'),
(16, 'SQL', 'Databases'),
(16, 'Hadoop', 'Tools'),
(16, 'Spark', 'Tools'),
(16, 'ETL', 'Tools');

-- Student 17 - Frontend/Backend Mix
INSERT INTO skills (user_id, name, skill_type) VALUES
(17, 'JavaScript', 'Programming Languages'),
(17, 'React', 'Frameworks'),
(17, 'Java', 'Programming Languages'),
(17, 'Spring Boot', 'Frameworks'),
(17, 'MySQL', 'Databases');

-- Student 18 - DevOps/Cloud Mix
INSERT INTO skills (user_id, name, skill_type) VALUES
(18, 'Docker', 'Tools'),
(18, 'AWS', 'Tools'),
(18, 'Terraform', 'Tools'),
(18, 'Linux', 'Tools');

-- Student 19 - Mobile/Web Mix
INSERT INTO skills (user_id, name, skill_type) VALUES
(19, 'React', 'Frameworks'),
(19, 'React Native', 'Frameworks'),
(19, 'JavaScript', 'Programming Languages'),
(19, 'HTML', 'Programming Languages'),
(19, 'CSS', 'Programming Languages');

-- Student 20 - Mixed Skills for Variety
INSERT INTO skills (user_id, name, skill_type) VALUES
(20, 'Python', 'Programming Languages'),
(20, 'JavaScript', 'Programming Languages'),
(20, 'React', 'Frameworks'),
(20, 'Docker', 'Tools'),
(20, 'Git', 'Tools'); 