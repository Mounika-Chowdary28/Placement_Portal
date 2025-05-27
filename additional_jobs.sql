-- SQL queries to insert additional jobs with diverse skills
-- Run these queries to populate your database with more varied job listings

-- Insert additional companies if needed
INSERT INTO companies (name, description, website) VALUES
('TechNova', 'Innovative software development firm specializing in mobile applications', 'technova.com'),
('DataMind Analytics', 'Data science and machine learning solutions provider', 'datamind.io'),
('CloudScale Systems', 'Cloud infrastructure and scaling solutions', 'cloudscale.tech'),
('SecurityFirst', 'Cybersecurity solutions and consulting', 'securityfirst.com'),
('MobileGenius', 'Mobile app development company', 'mobilegenius.app'),
('QuantumComputing', 'Quantum computing research and development', 'quantumcomputing.tech'),
('AIFuture', 'Artificial intelligence and machine learning firm', 'aifuture.ai'),
('WebDev Masters', 'Web development and design agency', 'webdevmasters.com'),
('BlockchainSolutions', 'Blockchain technology development', 'blockchainsolutions.io'),
('IoTInnovate', 'Internet of Things solutions provider', 'iotinnovate.tech');

-- Insert diverse job positions with varied skills
INSERT INTO jobs (company_id, title, description, location, job_type, salary_range, deadline, skills, requirements) VALUES
(1, 'Mobile App Developer', 'Join our team to build innovative mobile applications for iOS and Android platforms', 'Bangalore, India', 'Full-time', '₹10-16 LPA', '2023-12-30', 'Swift, Kotlin, Flutter, Firebase, Git', 'Bachelor\'s degree in Computer Science, 2+ years of mobile app development experience'),
(2, 'Data Engineer', 'Design and build data pipelines and data infrastructure', 'Mumbai, India', 'Full-time', '₹14-18 LPA', '2023-12-28', 'Python, SQL, Hadoop, Spark, ETL', 'Experience with big data technologies and cloud platforms'),
(3, 'Cloud Solutions Architect', 'Design and implement cloud-based solutions', 'Hyderabad, India', 'Full-time', '₹18-25 LPA', '2023-12-20', 'AWS, Azure, Docker, Kubernetes, Terraform', 'Cloud certification and 4+ years of experience'),
(4, 'Cybersecurity Analyst', 'Monitor and analyze security threats and implement security measures', 'Delhi, India', 'Full-time', '₹12-18 LPA', '2023-12-15', 'Network Security, Penetration Testing, SIEM, Ethical Hacking', 'Security certification and experience in threat detection'),
(5, 'React Native Developer', 'Develop cross-platform mobile apps using React Native', 'Chennai, India', 'Contract', '₹8-12 LPA', '2023-12-25', 'JavaScript, React Native, Redux, TypeScript', 'Experience building and deploying mobile apps'),
(6, 'Quantum Computing Researcher', 'Research and develop quantum computing algorithms', 'Bangalore, India', 'Full-time', '₹20-30 LPA', '2024-01-10', 'Quantum Computing, Physics, Mathematics, Python', 'PhD in Physics, Computer Science or related field'),
(7, 'Machine Learning Engineer', 'Develop and deploy machine learning models', 'Pune, India', 'Full-time', '₹15-22 LPA', '2023-12-18', 'Python, TensorFlow, PyTorch, Data Analysis, ML Algorithms', 'Strong background in machine learning and statistical analysis'),
(8, 'UX/UI Designer', 'Create user-centered designs for web and mobile applications', 'Remote', 'Full-time', '₹10-15 LPA', '2023-12-22', 'UI Design, UX Research, Figma, Adobe XD, Prototyping', 'Portfolio demonstrating UX/UI skills and experience'),
(9, 'Blockchain Developer', 'Develop and implement blockchain solutions', 'Hyderabad, India', 'Full-time', '₹16-24 LPA', '2024-01-05', 'Blockchain, Solidity, Ethereum, Smart Contracts, JavaScript', 'Experience with blockchain platforms and protocols'),
(10, 'IoT Solutions Developer', 'Design and develop IoT solutions for smart devices', 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-12-28', 'IoT, Embedded Systems, C++, MQTT, Cloud Integration', 'Experience with IoT platforms and embedded systems'),
(1, 'Backend Developer', 'Develop server-side logic and integrate with front-end elements', 'Delhi, India', 'Full-time', '₹12-16 LPA', '2023-12-20', 'Java, Spring Boot, Microservices, MySQL, API Design', 'Strong understanding of backend technologies and databases'),
(2, 'AI Research Scientist', 'Lead research in artificial intelligence and develop new algorithms', 'Mumbai, India', 'Full-time', '₹18-25 LPA', '2024-01-15', 'Deep Learning, Neural Networks, Python, Research, NLP', 'PhD or Master\'s degree in AI, Machine Learning or related field'),
(3, 'DevOps Engineer', 'Implement and maintain CI/CD pipelines and infrastructure', 'Pune, India', 'Full-time', '₹14-20 LPA', '2023-12-15', 'Jenkins, Docker, Kubernetes, Git, Linux, AWS', 'Experience with DevOps practices and tools'),
(4, 'Network Security Engineer', 'Design and implement network security solutions', 'Hyderabad, India', 'Full-time', '₹15-22 LPA', '2023-12-30', 'Network Security, Firewalls, VPN, IDS/IPS, SIEM', 'Security certification and network infrastructure experience'),
(5, 'Frontend Developer', 'Build responsive and interactive user interfaces', 'Chennai, India', 'Full-time', '₹10-15 LPA', '2023-12-25', 'JavaScript, React, Redux, HTML, CSS, Responsive Design', 'Portfolio showing frontend development skills'),
(6, 'Data Scientist', 'Apply data science techniques to solve business problems', 'Bangalore, India', 'Full-time', '₹16-22 LPA', '2024-01-10', 'Python, R, SQL, Statistics, Machine Learning, Data Visualization', 'Experience with data analysis and statistical modeling'),
(7, 'AR/VR Developer', 'Create augmented and virtual reality experiences', 'Mumbai, India', 'Full-time', '₹14-20 LPA', '2023-12-20', 'Unity3D, C#, 3D Modeling, AR Frameworks, VR Development', 'Experience developing AR/VR applications'),
(8, 'Full Stack Developer', 'Work on both frontend and backend aspects of web applications', 'Remote', 'Full-time', '₹12-18 LPA', '2023-12-28', 'JavaScript, React, Node.js, Express, MongoDB, RESTful APIs', 'Experience with full stack development and modern frameworks'),
(9, 'Game Developer', 'Develop engaging and interactive games', 'Pune, India', 'Full-time', '₹12-18 LPA', '2024-01-05', 'Unity, C#, Game Design, 3D Graphics, Physics', 'Portfolio of games and experience with game development'),
(10, 'Embedded Systems Engineer', 'Design and develop firmware for embedded devices', 'Bangalore, India', 'Full-time', '₹14-20 LPA', '2023-12-22', 'Embedded C, Microcontrollers, RTOS, Electronics, IoT', 'Experience with embedded systems development');

-- Remember to restart your PHP server after running these queries to ensure the new data is available 