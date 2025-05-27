<?php
// Database setup script - Run this once to create the database and tables

// Database configuration
$host = "localhost";
$username = "root";
$password = "";

// Create connection without database
$conn = mysqli_connect($host, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS placement_portal";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, "placement_portal");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    reg_number VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    personal_email VARCHAR(100),
    phone VARCHAR(20),
    dob DATE,
    branch VARCHAR(50),
    degree VARCHAR(50),
    year_of_study INT(1),
    cgpa DECIMAL(3,2),
    backlogs INT(2) DEFAULT 0,
    profile_pic VARCHAR(255),
    linkedin VARCHAR(255),
    github VARCHAR(255),
    preferred_roles TEXT,
    preferred_companies TEXT,
    location_preference VARCHAR(100),
    expected_salary VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

// Create companies table
$sql = "CREATE TABLE IF NOT EXISTS companies (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Companies table created successfully<br>";
} else {
    echo "Error creating companies table: " . mysqli_error($conn) . "<br>";
}

// Create jobs table
$sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    company_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    job_type VARCHAR(50),
    salary_range VARCHAR(50),
    deadline DATE,
    skills TEXT,
    requirements TEXT,
    min_cgpa DECIMAL(3,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Jobs table created successfully<br>";
} else {
    echo "Error creating jobs table: " . mysqli_error($conn) . "<br>";
}

// Create applications table
$sql = "CREATE TABLE IF NOT EXISTS applications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    job_id INT(11) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Applied',
    resume VARCHAR(255),
    cover_letter TEXT,
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    feedback TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Applications table created successfully<br>";
} else {
    echo "Error creating applications table: " . mysqli_error($conn) . "<br>";
}

// Create events table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(100),
    event_type VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT,
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Events table created successfully<br>";
} else {
    echo "Error creating events table: " . mysqli_error($conn) . "<br>";
}

// Create attendance table
$sql = "CREATE TABLE IF NOT EXISTS attendance (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    event_id INT(11) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'present',
    excuse_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Attendance table created successfully<br>";
} else {
    echo "Error creating attendance table: " . mysqli_error($conn) . "<br>";
}

// Create skills table
$sql = "CREATE TABLE IF NOT EXISTS skills (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    skill_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Skills table created successfully<br>";
} else {
    echo "Error creating skills table: " . mysqli_error($conn) . "<br>";
}

// Create certifications table
$sql = "CREATE TABLE IF NOT EXISTS certifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    issuer VARCHAR(100),
    issue_date DATE,
    expiry_date DATE,
    credential_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Certifications table created successfully<br>";
} else {
    echo "Error creating certifications table: " . mysqli_error($conn) . "<br>";
}

// Create projects table
$sql = "CREATE TABLE IF NOT EXISTS projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    technologies TEXT,
    project_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Projects table created successfully<br>";
} else {
    echo "Error creating projects table: " . mysqli_error($conn) . "<br>";
}

// Create notifications table
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Notifications table created successfully<br>";
} else {
    echo "Error creating notifications table: " . mysqli_error($conn) . "<br>";
}

// Create directory for profile pictures if it doesn't exist
if (!file_exists('../uploads/profile_pics')) {
    mkdir('../uploads/profile_pics', 0777, true);
}

// Create directory for resumes if it doesn't exist
if (!file_exists('../uploads/resumes')) {
    mkdir('../uploads/resumes', 0777, true);
}

// Insert sample companies
$companies = [
    ['TechCorp', 'Tech company specializing in software development'],
    ['DataSystems Inc', 'Data analytics and systems integration company'],
    ['WebTech Solutions', 'Web development and design company'],
    ['CloudSys Technologies', 'Cloud infrastructure and DevOps services'],
    ['InnovateX', 'Innovation and technology consulting firm'],
    ['GlobalTech', 'Global technology solutions provider'],
    ['Tech Solutions Inc.', 'IT solutions and services company'],
    ['Digital Dynamics', 'Digital transformation and consulting services'],
    ['Cyber Systems', 'Cybersecurity and network solutions provider'],
    ['AI Innovations', 'Artificial intelligence and machine learning company']
];

foreach ($companies as $company) {
    $name = mysqli_real_escape_string($conn, $company[0]);
    $description = mysqli_real_escape_string($conn, $company[1]);
    
    $sql = "INSERT INTO companies (name, description) 
            VALUES ('$name', '$description')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Company '$name' added successfully<br>";
    } else {
        echo "Error adding company '$name': " . mysqli_error($conn) . "<br>";
    }
}

// Insert sample jobs
$jobs = [
    [1, 'Software Development Engineer', 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', 7.5],
    [2, 'Data Scientist', 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', 8.0],
    [3, 'Frontend Developer', 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', 7.0],
    [4, 'DevOps Engineer', 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', 8.5],
    [5, 'Full Stack Developer', 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', 7.5],
    [6, 'Product Manager', 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', 8.0],
    [7, 'UI/UX Designer', 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', 7.0],
    [8, 'Backend Developer', 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', 7.5],
    [9, 'Machine Learning Engineer', 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', 8.5],
    [10, 'Software Engineer Intern', 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', 7.0]
];

foreach ($jobs as $job) {
    $company_id = $job[0];
    $title = mysqli_real_escape_string($conn, $job[1]);
    $location = mysqli_real_escape_string($conn, $job[2]);
    $job_type = mysqli_real_escape_string($conn, $job[3]);
    $salary_range = mysqli_real_escape_string($conn, $job[4]);
    $deadline = $job[5];
    $skills = mysqli_real_escape_string($conn, $job[6]);
    $requirements = mysqli_real_escape_string($conn, $job[7]);
    $min_cgpa = $job[8];
    
    $sql = "INSERT INTO jobs (company_id, title, location, job_type, salary_range, deadline, skills, requirements, min_cgpa) 
            VALUES ($company_id, '$title', '$location', '$job_type', '$salary_range', '$deadline', '$skills', '$requirements', $min_cgpa)";
    
    if (mysqli_query($conn, $sql)) {
        echo "Job '$title' added successfully<br>";
    } else {
        echo "Error adding job '$title': " . mysqli_error($conn) . "<br>";
    }
}

// Insert sample events
$events = [
    ['TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium'],
    ['DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204'],
    ['Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall'],
    ['Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room'],
    ['WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101']
];

foreach ($events as $event) {
    $event_name = mysqli_real_escape_string($conn, $event[0]);
    $company_name = mysqli_real_escape_string($conn, $event[1]);
    $event_type = mysqli_real_escape_string($conn, $event[2]);
    $event_date = $event[3];
    $description = mysqli_real_escape_string($conn, $event[4]);
    $location = mysqli_real_escape_string($conn, $event[5]);
    
    $sql = "INSERT INTO events (event_name, company_name, event_type, event_date, description, location) 
            VALUES ('$event_name', '$company_name', '$event_type', '$event_date', '$description', '$location')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Event '$event_name' added successfully<br>";
    } else {
        echo "Error adding event '$event_name': " . mysqli_error($conn) . "<br>";
    }
}

// Student data with profile pictures
$students = [
    ['AP23110011340', '15-05-2000', 'S Mounika Chowdary', 'mounika.s@srm.edu.in', 'smounikachowdary@gmail.com', '9876543210', '2000-05-15', 'Computer Science Engineering', 'B.Tech', 3, 8.75, 0, 'student1.jpg', 'linkedin.com/in/smounikachowdary', 'github.com/smounikachowdary'],
    ['AP23110011341', '22-06-1999', 'Rahul Sharma', 'rahul.s@srm.edu.in', 'rahulsharma@gmail.com', '9876543211', '1999-06-22', 'Computer Science Engineering', 'B.Tech', 3, 8.50, 0, 'student2.jpg', 'linkedin.com/in/rahulsharma', 'github.com/rahulsharma'],
    ['AP23110011342', '10-07-2000', 'Priya Patel', 'priya.p@srm.edu.in', 'priyapatel@gmail.com', '9876543212', '2000-07-10', 'Computer Science Engineering', 'B.Tech', 3, 9.20, 0, 'student3.jpg', 'linkedin.com/in/priyapatel', 'github.com/priyapatel'],
    ['AP23110011343', '05-03-2001', 'Amit Kumar', 'amit.k@srm.edu.in', 'amitkumar@gmail.com', '9876543213', '2001-03-05', 'Computer Science Engineering', 'B.Tech', 3, 7.90, 1, 'student4.jpg', 'linkedin.com/in/amitkumar', 'github.com/amitkumar'],
    ['AP23110011344', '18-11-2000', 'Sneha Reddy', 'sneha.r@srm.edu.in', 'snehareddy@gmail.com', '9876543214', '2000-11-18', 'Computer Science Engineering', 'B.Tech', 3, 8.80, 0, 'student5.jpg', 'linkedin.com/in/snehareddy', 'github.com/snehareddy'],
    ['AP23110011345', '30-09-1999', 'Vikram Singh', 'vikram.s@srm.edu.in', 'vikramsingh@gmail.com', '9876543215', '1999-09-30', 'Electronics Engineering', 'B.Tech', 3, 8.10, 0, 'student6.jpg', 'linkedin.com/in/vikramsingh', 'github.com/vikramsingh'],
    ['AP23110011346', '12-04-2000', 'Neha Gupta', 'neha.g@srm.edu.in', 'nehagupta@gmail.com', '9876543216', '2000-04-12', 'Electronics Engineering', 'B.Tech', 3, 8.60, 0, 'student7.jpg', 'linkedin.com/in/nehagupta', 'github.com/nehagupta'],
    ['AP23110011347', '25-08-2000', 'Arjun Nair', 'arjun.n@srm.edu.in', 'arjunnair@gmail.com', '9876543217', '2000-08-25', 'Mechanical Engineering', 'B.Tech', 3, 7.80, 1, 'student8.jpg', 'linkedin.com/in/arjunnair', 'github.com/arjunnair'],
    ['AP23110011348', '14-02-2001', 'Divya Sharma', 'divya.s@srm.edu.in', 'divyasharma@gmail.com', '9876543218', '2001-02-14', 'Mechanical Engineering', 'B.Tech', 3, 8.30, 0, 'student9.jpg', 'linkedin.com/in/divyasharma', 'github.com/divyasharma'],
    ['AP23110011349', '08-12-2000', 'Karthik Menon', 'karthik.m@srm.edu.in', 'karthikmenon@gmail.com', '9876543219', '2000-12-08', 'Civil Engineering', 'B.Tech', 3, 7.70, 1, 'student10.jpg', 'linkedin.com/in/karthikmenon', 'github.com/karthikmenon'],
    ['AP23110011350', '19-10-1999', 'Ananya Desai', 'ananya.d@srm.edu.in', 'ananyaDesai@gmail.com', '9876543220', '1999-10-19', 'Civil Engineering', 'B.Tech', 3, 8.40, 0, 'student11.jpg', 'linkedin.com/in/ananyaDesai', 'github.com/ananyaDesai'],
    ['AP23110011351', '27-01-2000', 'Rohan Joshi', 'rohan.j@srm.edu.in', 'rohanjoshi@gmail.com', '9876543221', '2000-01-27', 'Information Technology', 'B.Tech', 3, 9.10, 0, 'student12.jpg', 'linkedin.com/in/rohanjoshi', 'github.com/rohanjoshi'],
    ['AP23110011352', '03-06-2001', 'Meera Krishnan', 'meera.k@srm.edu.in', 'meerakrishnan@gmail.com', '9876543222', '2001-06-03', 'Information Technology', 'B.Tech', 3, 8.90, 0, 'student13.jpg', 'linkedin.com/in/meerakrishnan', 'github.com/meerakrishnan'],
    ['AP23110011353', '16-07-2000', 'Aditya Verma', 'aditya.v@srm.edu.in', 'adityaverma@gmail.com', '9876543223', '2000-07-16', 'Computer Science Engineering', 'B.Tech', 3, 8.20, 0, 'student14.jpg', 'linkedin.com/in/adityaverma', 'github.com/adityaverma'],
    ['AP23110011354', '29-04-1999', 'Kavya Rao', 'kavya.r@srm.edu.in', 'kavyarao@gmail.com', '9876543224', '1999-04-29', 'Computer Science Engineering', 'B.Tech', 3, 8.70, 0, 'student15.jpg', 'linkedin.com/in/kavyarao', 'github.com/kavyarao'],
    ['AP23110011355', '11-11-2000', 'Siddharth Patel', 'siddharth.p@srm.edu.in', 'siddharthpatel@gmail.com', '9876543225', '2000-11-11', 'Electronics Engineering', 'B.Tech', 3, 7.60, 2, 'student16.jpg', 'linkedin.com/in/siddharthpatel', 'github.com/siddharthpatel'],
    ['AP23110011356', '23-02-2001', 'Riya Malhotra', 'riya.m@srm.edu.in', 'riyamalhotra@gmail.com', '9876543226', '2001-02-23', 'Electronics Engineering', 'B.Tech', 3, 8.00, 0, 'student17.jpg', 'linkedin.com/in/riyamalhotra', 'github.com/riyamalhotra'],
    ['AP23110011357', '07-08-2000', 'Varun Kapoor', 'varun.k@srm.edu.in', 'varunkapoor@gmail.com', '9876543227', '2000-08-07', 'Mechanical Engineering', 'B.Tech', 3, 7.50, 1, 'student18.jpg', 'linkedin.com/in/varunkapoor', 'github.com/varunkapoor'],
    ['AP23110011358', '14-05-1999', 'Ishita Sharma', 'ishita.s@srm.edu.in', 'ishitasharma@gmail.com', '9876543228', '1999-05-14', 'Mechanical Engineering', 'B.Tech', 3, 8.85, 0, 'student19.jpg', 'linkedin.com/in/ishitasharma', 'github.com/ishitasharma'],
    ['AP23110011359', '02-09-2000', 'Nikhil Mehta', 'nikhil.m@srm.edu.in', 'nikhilmehta@gmail.com', '9876543229', '2000-09-02', 'Civil Engineering', 'B.Tech', 3, 7.95, 0, 'student20.jpg', 'linkedin.com/in/nikhilmehta', 'github.com/nikhilmehta']
];

// Insert student data
foreach ($students as $student) {
    $reg_number = mysqli_real_escape_string($conn, $student[0]);
    $password = password_hash($student[1], PASSWORD_DEFAULT); // Hash the password (DOB in dd-mm-yyyy format)
    $full_name = mysqli_real_escape_string($conn, $student[2]);
    $email = mysqli_real_escape_string($conn, $student[3]);
    $personal_email = mysqli_real_escape_string($conn, $student[4]);
    $phone = mysqli_real_escape_string($conn, $student[5]);
    $dob = $student[6];
    $branch = mysqli_real_escape_string($conn, $student[7]);
    $degree = mysqli_real_escape_string($conn, $student[8]);
    $year_of_study = $student[9];
    $cgpa = $student[10];
    $backlogs = $student[11];
    $profile_pic = mysqli_real_escape_string($conn, $student[12]);
    $linkedin = mysqli_real_escape_string($conn, $student[13]);
    $github = mysqli_real_escape_string($conn, $student[14]);
    
    $sql = "INSERT INTO users (reg_number, password, full_name, email, personal_email, phone, dob, branch, degree, year_of_study, cgpa, backlogs, profile_pic, linkedin, github) 
            VALUES ('$reg_number', '$password', '$full_name', '$email', '$personal_email', '$phone', '$dob', '$branch', '$degree', $year_of_study, $cgpa, $backlogs, '$profile_pic', '$linkedin', '$github')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Student '$full_name' added successfully<br>";
    } else {
        echo "Error adding student '$full_name': " . mysqli_error($conn) . "<br>";
    }
}

// Insert sample skills for students
$skillTypes = ['Programming Languages', 'Frameworks', 'Databases', 'Tools', 'Soft Skills'];
$skills = [
    'Programming Languages' => ['Java', 'Python', 'JavaScript', 'C++', 'TypeScript', 'PHP', 'C#', 'Ruby', 'Swift', 'Go'],
    'Frameworks' => ['React', 'Angular', 'Vue.js', 'Spring Boot', 'Django', 'Flask', 'Express.js', 'Laravel', 'ASP.NET', 'Ruby on Rails'],
    'Databases' => ['MySQL', 'MongoDB', 'PostgreSQL', 'SQLite', 'Oracle', 'SQL Server', 'Redis', 'Firebase', 'Cassandra', 'DynamoDB'],
    'Tools' => ['Git', 'Docker', 'Kubernetes', 'Jenkins', 'AWS', 'Azure', 'GCP', 'Jira', 'Confluence', 'Postman'],
    'Soft Skills' => ['Communication', 'Teamwork', 'Problem Solving', 'Time Management', 'Leadership', 'Adaptability', 'Creativity', 'Critical Thinking', 'Emotional Intelligence', 'Conflict Resolution']
];

// Add skills for each student
for ($i = 1; $i <= 20; $i++) {
    // Add 5-10 random skills for each student
    $numSkills = rand(5, 10);
    $addedSkills = [];
    
    for ($j = 0; $j < $numSkills; $j++) {
        $skillType = $skillTypes[array_rand($skillTypes)];
        $skillName = $skills[$skillType][array_rand($skills[$skillType])];
        
        // Avoid duplicate skills for the same student
        $skillKey = $skillType . '-' . $skillName;
        if (in_array($skillKey, $addedSkills)) {
            continue;
        }
        
        $addedSkills[] = $skillKey;
        
        $sql = "INSERT INTO skills (user_id, name, skill_type) 
                VALUES ($i, '" . mysqli_real_escape_string($conn, $skillName) . "', '" . mysqli_real_escape_string($conn, $skillType) . "')";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added skills for student ID $i<br>";
}

// Insert sample certifications for students
$certifications = [
    ['AWS Certified Solutions Architect', 'Amazon Web Services', '2022-01-15', '2025-01-15', 'aws.com/certification/12345'],
    ['Google Cloud Associate Engineer', 'Google Cloud', '2022-02-20', '2025-02-20', 'google.com/certification/12345'],
    ['Microsoft Certified: Azure Developer', 'Microsoft', '2022-03-10', '2025-03-10', 'microsoft.com/certification/12345'],
    ['Oracle Certified Professional: Java SE', 'Oracle', '2022-04-05', '2025-04-05', 'oracle.com/certification/12345'],
    ['Certified Kubernetes Administrator', 'Cloud Native Computing Foundation', '2022-05-12', '2025-05-12', 'cncf.io/certification/12345'],
    ['Certified Scrum Master', 'Scrum Alliance', '2022-06-18', '2024-06-18', 'scrumalliance.org/certification/12345'],
    ['CompTIA Security+', 'CompTIA', '2022-07-22', '2025-07-22', 'comptia.org/certification/12345'],
    ['Cisco Certified Network Associate', 'Cisco', '2022-08-30', '2025-08-30', 'cisco.com/certification/12345'],
    ['MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345'],
    ['Certified Ethical Hacker', 'EC-Council', '2022-10-05', '2025-10-05', 'eccouncil.org/certification/12345']
];

// Add certifications for some students
for ($i = 1; $i <= 15; $i++) {
    // Add 1-3 random certifications for each student
    $numCerts = rand(1, 3);
    $addedCerts = [];
    
    for ($j = 0; $j < $numCerts; $j++) {
        $certIndex = array_rand($certifications);
        
        // Avoid duplicate certifications for the same student
        if (in_array($certIndex, $addedCerts)) {
            continue;
        }
        
        $addedCerts[] = $certIndex;
        $cert = $certifications[$certIndex];
        
        $sql = "INSERT INTO certifications (user_id, name, issuer, issue_date, expiry_date, credential_url) 
                VALUES ($i, '" . mysqli_real_escape_string($conn, $cert[0]) . "', '" . mysqli_real_escape_string($conn, $cert[1]) . "', 
                '" . $cert[2] . "', '" . $cert[3] . "', '" . mysqli_real_escape_string($conn, $cert[4]) . "')";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added certifications for student ID $i<br>";
}

// Insert sample projects for students
$projects = [
    ['E-Commerce Website', 'Developed a full-stack e-commerce platform with product listings, shopping cart, and payment integration', 'React, Node.js, MongoDB, Stripe', 'github.com/user/ecommerce-project'],
    ['Smart Attendance System', 'Created an automated attendance system using facial recognition technology', 'Python, OpenCV, TensorFlow, Flask', 'github.com/user/attendance-system'],
    ['Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager'],
    ['Weather Forecast App', 'Developed a weather forecast application using public APIs', 'JavaScript, HTML, CSS, OpenWeatherMap API', 'github.com/user/weather-app'],
    ['Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system'],
    ['Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard'],
    ['Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker'],
    ['Blog Platform', 'Created a blog platform with content management system', 'PHP, MySQL, Bootstrap, jQuery', 'github.com/user/blog-platform'],
    ['Movie Recommendation System', 'Built a recommendation system using machine learning algorithms', 'Python, Scikit-learn, Pandas, Flask', 'github.com/user/movie-recommender'],
    ['Chat Application', 'Developed a real-time chat application with private and group messaging', 'Socket.io, Express.js, MongoDB, React', 'github.com/user/chat-app']
];

// Add projects for students
for ($i = 1; $i <= 20; $i++) {
    // Add 1-3 random projects for each student
    $numProjects = rand(1, 3);
    $addedProjects = [];
    
    for ($j = 0; $j < $numProjects; $j++) {
        $projectIndex = array_rand($projects);
        
        // Avoid duplicate projects for the same student
        if (in_array($projectIndex, $addedProjects)) {
            continue;
        }
        
        $addedProjects[] = $projectIndex;
        $project = $projects[$projectIndex];
        
        $sql = "INSERT INTO projects (user_id, title, description, technologies, project_url) 
                VALUES ($i, '" . mysqli_real_escape_string($conn, $project[0]) . "', '" . mysqli_real_escape_string($conn, $project[1]) . "', 
                '" . mysqli_real_escape_string($conn, $project[2]) . "', '" . mysqli_real_escape_string($conn, $project[3]) . "')";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added projects for student ID $i<br>";
}

// Insert sample applications for students
$applicationStatuses = ['Applied', 'Application Under Review', 'Technical Assessment', 'Interview Scheduled', 'Final Interview', 'Offer Received', 'Rejected', 'Withdrawn'];

// Add applications for students
for ($i = 1; $i <= 20; $i++) {
    // Add 0-5 random applications for each student
    $numApplications = rand(0, 5);
    $addedJobs = [];
    
    for ($j = 0; $j < $numApplications; $j++) {
        $jobId = rand(1, 10);
        
        // Avoid duplicate applications for the same job
        if (in_array($jobId, $addedJobs)) {
            continue;
        }
        
        $addedJobs[] = $jobId;
        $status = $applicationStatuses[array_rand($applicationStatuses)];
        $resume = 'resume_' . $i . '_' . $jobId . '.pdf';
        $coverLetter = 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.';
        
        $sql = "INSERT INTO applications (user_id, job_id, status, resume, cover_letter) 
                VALUES ($i, $jobId, '" . mysqli_real_escape_string($conn, $status) . "', 
                '" . mysqli_real_escape_string($conn, $resume) . "', '" . mysqli_real_escape_string($conn, $coverLetter) . "')";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added applications for student ID $i<br>";
}

// Insert sample notifications for students
$notifications = [
    ['Interview Reminder', 'Your first round interview with TechCorp is tomorrow at 10:00 AM.'],
    ['Application Incomplete', 'Please complete your application for DataSystems Inc.'],
    ['New Job Posted', 'A new job matching your profile has been posted by InnovateX.'],
    ['Application Status Update', 'Your application for WebTech Solutions has been reviewed.'],
    ['Resume Feedback', 'You have received feedback on your resume from the placement officer.'],
    ['Event Reminder', 'Don\'t forget to attend the Resume Building Workshop tomorrow.'],
    ['Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.'],
    ['Offer Letter', 'Congratulations! You have received an offer letter from TechCorp.'],
    ['Application Rejected', 'We regret to inform you that your application for GlobalTech has been rejected.'],
    ['Profile Completion', 'Your profile is 85% complete. Add more details to improve visibility to recruiters.']
];

// Add notifications for students
for ($i = 1; $i <= 20; $i++) {
    // Add 2-5 random notifications for each student
    $numNotifications = rand(2, 5);
    $addedNotifications = [];
    
    for ($j = 0; $j < $numNotifications; $j++) {
        $notificationIndex = array_rand($notifications);
        
        // Avoid duplicate notifications for the same student
        if (in_array($notificationIndex, $addedNotifications)) {
            continue;
        }
        
        $addedNotifications[] = $notificationIndex;
        $notification = $notifications[$notificationIndex];
        
        $sql = "INSERT INTO notifications (user_id, title, message, is_read) 
                VALUES ($i, '" . mysqli_real_escape_string($conn, $notification[0]) . "', 
                '" . mysqli_real_escape_string($conn, $notification[1]) . "', " . rand(0, 1) . ")";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added notifications for student ID $i<br>";
}

// Add attendance records for events
for ($eventId = 1; $eventId <= 5; $eventId++) {
    // Randomly select 10-15 students for each event
    $numStudents = rand(10, 15);
    $selectedStudents = array_rand(range(1, 20), $numStudents);
    
    if (!is_array($selectedStudents)) {
        $selectedStudents = [$selectedStudents];
    }
    
    foreach ($selectedStudents as $studentId) {
        $studentId = $studentId + 1; // Adjust for 0-based array
        $status = rand(0, 10) > 2 ? 'present' : 'absent'; // 80% chance of being present
        $excuseReason = $status === 'absent' ? 'Personal reasons' : NULL;
        
        $sql = "INSERT INTO attendance (student_id, event_id, status, excuse_reason) 
                VALUES ($studentId, $eventId, '$status', " . ($excuseReason ? "'" . mysqli_real_escape_string($conn, $excuseReason) . "'" : "NULL") . ")";
        
        mysqli_query($conn, $sql);
    }
    
    echo "Added attendance records for event ID $eventId<br>";
}

echo "<br><strong>Database setup completed successfully!</strong>";
echo "<br><strong>Next steps:</strong>";
echo "<br>1. Copy the profile pictures to the 'uploads/profile_pics' directory";
echo "<br>2. Access the portal at <a href='../index.php'>index.php</a>";
echo "<br>3. Login with any student's registration number and password (DOB in dd-mm-yyyy format)";

mysqli_close($conn);
?>