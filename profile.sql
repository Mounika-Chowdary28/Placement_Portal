CREATE TABLE IF NOT EXISTS students (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    reg_number VARCHAR(20) NOT NULL UNIQUE COMMENT 'Student registration number (e.g. AP23110011340)',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    full_name VARCHAR(100) NOT NULL COMMENT 'Student full name',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Institutional email',
    personal_email VARCHAR(100) COMMENT 'Personal email address',
    phone VARCHAR(20) COMMENT 'Contact phone number',
    dob DATE COMMENT 'Date of birth',
    department VARCHAR(100) COMMENT 'Department/major',
    degree VARCHAR(50) COMMENT 'Degree program (e.g. B.Tech)',
    year INT(1) COMMENT 'Current year of study',
    cgpa DECIMAL(3,2) COMMENT 'Cumulative GPA',
    backlogs INT(2) DEFAULT 0 COMMENT 'Number of backlogs/failed courses',
    profile_image VARCHAR(255) DEFAULT 'default.jpg' COMMENT 'Profile photo filename',
    linkedin VARCHAR(255) COMMENT 'LinkedIn profile URL',
    github VARCHAR(255) COMMENT 'GitHub profile URL',
    address TEXT COMMENT 'Residential address',
    city VARCHAR(50) COMMENT 'City of residence',
    state VARCHAR(50) COMMENT 'State/province',
    country VARCHAR(50) DEFAULT 'India' COMMENT 'Country',
    postal_code VARCHAR(20) COMMENT 'Postal/ZIP code',
    bio TEXT COMMENT 'Student bio/about me',
    resume_url VARCHAR(255) COMMENT 'Path to uploaded resume file',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT(11) NOT NULL COMMENT 'Foreign key to users table',
    graduation_year INT(4) COMMENT 'Expected graduation year',
    enrollment_number VARCHAR(50) COMMENT 'University enrollment number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create education table
CREATE TABLE IF NOT EXISTS education (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    degree VARCHAR(100),
    field_of_study VARCHAR(100),
    start_date DATE,
    end_date DATE,
    grade VARCHAR(20),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create experience table
CREATE TABLE IF NOT EXISTS experience (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    company VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    start_date DATE,
    end_date DATE,
    is_current TINYINT(1) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    completion_date DATE,
    project_url VARCHAR(255),
    technologies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create certifications table
CREATE TABLE IF NOT EXISTS certifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    issuing_organization VARCHAR(100),
    issue_date DATE,
    expiry_date DATE,
    credential_id VARCHAR(100),
    credential_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create documents table
CREATE TABLE IF NOT EXISTS documents (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    document_name VARCHAR(100) NOT NULL,
    document_type VARCHAR(50),
    file_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create skills table
CREATE TABLE IF NOT EXISTS skills (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    skill_type VARCHAR(50),
    proficiency INT(1) DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert student records from the snippet data
INSERT INTO students (
    id, reg_number, password, full_name, email, personal_email, 
    phone, dob, department, degree, year, cgpa, backlogs, 
    profile_image, linkedin, github, created_at, updated_at, user_id
) VALUES
(1, 'AP23110011340', '$2y$10$psX.JgMzob8XC9KqzfVSbuYikC.CMIWfCru01Q9sG3y...', 'S Mounika Chowdary', 'mounika.s@srm.edu.in', 'smounikachowdary@gmail.com', 
 '9876543210', '2006-07-28', 'Computer Science Engineering', 'B.Tech', 3, 8.75, 0, 
 'student1.jpg', 'linkedin.com/in/smounikachowdary', 'github.com/smounikachowdary', '2025-04-18 11:56:17', '2025-04-18 11:57:12', 1),

(2, 'AP23110011341', '$2y$10$6Chlyv7t0Ocjhvwrdu.S4usw2Z9kuqhyjKnYfmYmDEe...', 'Rahul Sharma', 'rahul.s@srm.edu.in', 'rahulsharma@gmail.com', 
 '9876543211', '1999-06-22', 'Computer Science Engineering', 'B.Tech', 3, 8.50, 0, 
 'student2.jpg', 'linkedin.com/in/rahulsharma', 'github.com/rahulsharma', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 2),

(3, 'AP23110011342', '$2y$10$Fw2DAbR0pJS9h.bxFBFm9.nfDu4e5luMn0dt2y5Q/3e...', 'Priya Patel', 'priya.p@srm.edu.in', 'priyapatel@gmail.com', 
 '9876543212', '2000-07-10', 'Computer Science Engineering', 'B.Tech', 3, 9.20, 0, 
 'student3.jpg', 'linkedin.com/in/priyapatel', 'github.com/priyapatel', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 3),

(4, 'AP23110011343', '$2y$10$vrV5qQVsGoWTRTVUj1oJCOjPexjSIZfFVGX1JPxnHIk...', 'Amit Kumar', 'amit.k@srm.edu.in', 'amitkumar@gmail.com', 
 '9876543213', '2001-03-05', 'Computer Science Engineering', 'B.Tech', 3, 7.90, 1, 
 'student4.jpg', 'linkedin.com/in/amitkumar', 'github.com/amitkumar', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 4),

(5, 'AP23110011344', '$2y$10$foOztje8ayyi.YBb2PPbPeEhKdHdOBxtHALdTwDZne8...', 'Sneha Reddy', 'sneha.r@srm.edu.in', 'snehareddy@gmail.com', 
 '9876543214', '2000-11-18', 'Computer Science Engineering', 'B.Tech', 3, 8.80, 0, 
 'student5.jpg', 'linkedin.com/in/snehareddy', 'github.com/snehareddy', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 5),

(6, 'AP23110011345', '$2y$10$igq5p4R/w4epkGy4QWS3FuLaOxup28yuPnSaiTN.cMJ...', 'Vikram Singh', 'vikram.s@srm.edu.in', 'vikramsingh@gmail.com', 
 '9876543215', '1999-09-30', 'Electronics Engineering', 'B.Tech', 3, 8.10, 0, 
 'student6.jpg', 'linkedin.com/in/vikramsingh', 'github.com/vikramsingh', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 6),

(7, 'AP23110011346', '$2y$10$rxHNtOh1J7XQ8x15q839W.fzuLeewcCywWcyPRE0qvO...', 'Neha Gupta', 'neha.g@srm.edu.in', 'nehagupta@gmail.com', 
 '9876543216', '2000-04-12', 'Electronics Engineering', 'B.Tech', 3, 8.60, 0, 
 'student7.jpg', 'linkedin.com/in/nehagupta', 'github.com/nehagupta', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 7),

(8, 'AP23110011347', '$2y$10$GsFU1WSXsPj41TsE9N1cxuC44pxIaO5qMLBTUBf9qsg...', 'Arjun Nair', 'arjun.n@srm.edu.in', 'arjunnair@gmail.com', 
 '9876543217', '2000-08-25', 'Mechanical Engineering', 'B.Tech', 3, 7.80, 1, 
 'student8.jpg', 'linkedin.com/in/arjunnair', 'github.com/arjunnair', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 8),

(9, 'AP23110011348', '$2y$10$WCDdkcQ1R/o0C.e/CnvcW.bnN0wRNRWOYF7opc8MHJR...', 'Divya Sharma', 'divya.s@srm.edu.in', 'divyasharma@gmail.com', 
 '9876543218', '2001-02-14', 'Mechanical Engineering', 'B.Tech', 3, 8.30, 0, 
 'student9.jpg', 'linkedin.com/in/divyasharma', 'github.com/divyasharma', '2025-04-18 11:56:18', '2025-04-18 11:56:18', 9),

(10, 'AP23110011349', '$2y$10$Kl5s6Ib08cw9H8xX2HEFWu47cKAgygzACT.brNCA7xj...', 'Karthik Menon', 'karthik.m@srm.edu.in', 'karthikmenon@gmail.com', 
 '9876543219', '2000-12-08', 'Civil Engineering', 'B.Tech', 3, 7.70, 1, 
 'student10.jpg', 'linkedin.com/in/karthikmenon', 'github.com/karthikmenon', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 10),

(11, 'AP23110011350', '$2y$10$cRVPknxZu68S.ik8RYYNFOlFxsGtu6QG3puq0CEqNIV...', 'Ananya Desai', 'ananya.d@srm.edu.in', 'ananyaDesai@gmail.com', 
 '9876543220', '1999-10-19', 'Civil Engineering', 'B.Tech', 3, 8.40, 0, 
 'student11.jpg', 'linkedin.com/in/ananyaDesai', 'github.com/ananyaDesai', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 11),

(12, 'AP23110011351', '$2y$10$z1n3RmBZGzNhUaiAkTfJ.ugFdeCAbiKu1fD16zYEviJ...', 'Rohan Joshi', 'rohan.j@srm.edu.in', 'rohanjoshi@gmail.com', 
 '9876543221', '2000-01-27', 'Information Technology', 'B.Tech', 3, 9.10, 0, 
 'student12.jpg', 'linkedin.com/in/rohanjoshi', 'github.com/rohanjoshi', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 12),

(13, 'AP23110011352', '$2y$10$9x8sLJ9fiGmhPPMjQcQ/5eKPh/4thOpRnA0blhZedeR...', 'Meera Krishnan', 'meera.k@srm.edu.in', 'meerakrishnan@gmail.com', 
 '9876543222', '2001-06-03', 'Information Technology', 'B.Tech', 3, 8.90, 0, 
 'student13.jpg', 'linkedin.com/in/meerakrishnan', 'github.com/meerakrishnan', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 13),

(14, 'AP23110011353', '$2y$10$TJNboTW0DOqkyVYovBv9LOernt5OcVGzGsXIjn70xgg...', 'Aditya Verma', 'aditya.v@srm.edu.in', 'adityaverma@gmail.com', 
 '9876543223', '2000-07-16', 'Computer Science Engineering', 'B.Tech', 3, 8.20, 0, 
 'student14.jpg', 'linkedin.com/in/adityaverma', 'github.com/adityaverma', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 14),

(15, 'AP23110011354', '$2y$10$WEXGwO3tsBesnsXyFQ.Kd./RzaV06dW1Qc7qjqjXlJ6...', 'Kavya Rao', 'kavya.r@srm.edu.in', 'kavyarao@gmail.com', 
 '9876543224', '1999-04-29', 'Computer Science Engineering', 'B.Tech', 3, 8.70, 0, 
 'student15.jpg', 'linkedin.com/in/kavyarao', 'github.com/kavyarao', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 15),

(16, 'AP23110011355', '$2y$10$m1yi03t7S.oVDAoNMCSXH.mNtNRwdMvqYnQbJFrBx9u...', 'Siddharth Patel', 'siddharth.p@srm.edu.in', 'siddharthpatel@gmail.com', 
 '9876543225', '2000-11-11', 'Electronics Engineering', 'B.Tech', 3, 7.60, 2, 
 'student16.jpg', 'linkedin.com/in/siddharthpatel', 'github.com/siddharthpatel', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 16),

(17, 'AP23110011356', '$2y$10$9PyQMLMbG5IhjCZaLbJvDeF4sdkd0VCvgdwk6hoBFh3...', 'Riya Malhotra', 'riya.m@srm.edu.in', 'riyamalhotra@gmail.com', 
 '9876543226', '2001-02-23', 'Electronics Engineering', 'B.Tech', 3, 8.00, 0, 
 'student17.jpg', 'linkedin.com/in/riyamalhotra', 'github.com/riyamalhotra', '2025-04-18 11:56:19', '2025-04-18 11:56:19', 17),

(18, 'AP23110011357', '$2y$10$/MPCBO1sdGlyH4ybVlReYen62nAg.uEjw1jJI4aeDSB...', 'Varun Kapoor', 'varun.k@srm.edu.in', 'varunkapoor@gmail.com', 
 '9876543227', '2000-08-07', 'Mechanical Engineering', 'B.Tech', 3, 7.50, 1, 
 'student18.jpg', 'linkedin.com/in/varunkapoor', 'github.com/varunkapoor', '2025-04-18 11:56:20', '2025-04-18 11:56:20', 18),

(19, 'AP23110011358', '$2y$10$bdzyrz/KFDh6ixUuplsibeqeQGaR9zRBbS1Q/cCBctz...', 'Ishita Sharma', 'ishita.s@srm.edu.in', 'ishitasharma@gmail.com', 
 '9876543228', '1999-05-14', 'Mechanical Engineering', 'B.Tech', 3, 8.85, 0, 
 'student19.jpg', 'linkedin.com/in/ishitasharma', 'github.com/ishitasharma', '2025-04-18 11:56:20', '2025-04-18 11:56:20', 19),

(20, 'AP23110011359', '$2y$10$IzNSTzvNuGX1qwyvWzAnxOBoK701m4rjW30qInd2lQ3...', 'Nikhil Mehta', 'nikhil.m@srm.edu.in', 'nikhilmehta@gmail.com', 
 '9876543229', '2000-09-02', 'Civil Engineering', 'B.Tech', 3, 7.95, 0, 
 'student20.jpg', 'linkedin.com/in/nikhilmehta', 'github.com/nikhilmehta', '2025-04-18 11:56:20', '2025-04-18 11:56:20', 20);