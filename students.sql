-- Create students table
CREATE TABLE IF NOT EXISTS students (
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
);

-- Insert all 20 students
INSERT INTO students (reg_number, password, full_name, email, personal_email, phone, dob, branch, degree, year_of_study, cgpa, backlogs, profile_pic, linkedin, github) 
VALUES 
('AP23110011340', SHA2('15-05-2000', 256), 'S Mounika Chowdary', 'mounika.s@srm.edu.in', 'smounikachowdary@gmail.com', '9876543210', '2000-05-15', 'Computer Science Engineering', 'B.Tech', 3, 8.75, 0, 'student1.jpg', 'linkedin.com/in/smounikachowdary', 'github.com/smounikachowdary'),
('AP23110011341', SHA2('22-06-1999', 256), 'Rahul Sharma', 'rahul.s@srm.edu.in', 'rahulsharma@gmail.com', '9876543211', '1999-06-22', 'Computer Science Engineering', 'B.Tech', 3, 8.50, 0, 'student2.jpg', 'linkedin.com/in/rahulsharma', 'github.com/rahulsharma'),
('AP23110011342', SHA2('10-07-2000', 256), 'Priya Patel', 'priya.p@srm.edu.in', 'priyapatel@gmail.com', '9876543212', '2000-07-10', 'Computer Science Engineering', 'B.Tech', 3, 9.20, 0, 'student3.jpg', 'linkedin.com/in/priyapatel', 'github.com/priyapatel'),
('AP23110011343', SHA2('05-03-2001', 256), 'Amit Kumar', 'amit.k@srm.edu.in', 'amitkumar@gmail.com', '9876543213', '2001-03-05', 'Computer Science Engineering', 'B.Tech', 3, 7.90, 1, 'student4.jpg', 'linkedin.com/in/amitkumar', 'github.com/amitkumar'),
('AP23110011344', SHA2('18-11-2000', 256), 'Sneha Reddy', 'sneha.r@srm.edu.in', 'snehareddy@gmail.com', '9876543214', '2000-11-18', 'Computer Science Engineering', 'B.Tech', 3, 8.80, 0, 'student5.jpg', 'linkedin.com/in/snehareddy', 'github.com/snehareddy'),
('AP23110011345', SHA2('30-09-1999', 256), 'Vikram Singh', 'vikram.s@srm.edu.in', 'vikramsingh@gmail.com', '9876543215', '1999-09-30', 'Electronics Engineering', 'B.Tech', 3, 8.10, 0, 'student6.jpg', 'linkedin.com/in/vikramsingh', 'github.com/vikramsingh'),
('AP23110011346', SHA2('12-04-2000', 256), 'Neha Gupta', 'neha.g@srm.edu.in', 'nehagupta@gmail.com', '9876543216', '2000-04-12', 'Electronics Engineering', 'B.Tech', 3, 8.60, 0, 'student7.jpg', 'linkedin.com/in/nehagupta', 'github.com/nehagupta'),
('AP23110011347', SHA2('25-08-2000', 256), 'Arjun Nair', 'arjun.n@srm.edu.in', 'arjunnair@gmail.com', '9876543217', '2000-08-25', 'Mechanical Engineering', 'B.Tech', 3, 7.80, 1, 'student8.jpg', 'linkedin.com/in/arjunnair', 'github.com/arjunnair'),
('AP23110011348', SHA2('14-02-2001', 256), 'Divya Sharma', 'divya.s@srm.edu.in', 'divyasharma@gmail.com', '9876543218', '2001-02-14', 'Mechanical Engineering', 'B.Tech', 3, 8.30, 0, 'student9.jpg', 'linkedin.com/in/divyasharma', 'github.com/divyasharma'),
('AP23110011349', SHA2('08-12-2000', 256), 'Karthik Menon', 'karthik.m@srm.edu.in', 'karthikmenon@gmail.com', '9876543219', '2000-12-08', 'Civil Engineering', 'B.Tech', 3, 7.70, 1, 'student10.jpg', 'linkedin.com/in/karthikmenon', 'github.com/karthikmenon'),
('AP23110011350', SHA2('19-10-1999', 256), 'Ananya Desai', 'ananya.d@srm.edu.in', 'ananyaDesai@gmail.com', '9876543220', '1999-10-19', 'Civil Engineering', 'B.Tech', 3, 8.40, 0, 'student11.jpg', 'linkedin.com/in/ananyaDesai', 'github.com/ananyaDesai'),
('AP23110011351', SHA2('27-01-2000', 256), 'Rohan Joshi', 'rohan.j@srm.edu.in', 'rohanjoshi@gmail.com', '9876543221', '2000-01-27', 'Information Technology', 'B.Tech', 3, 9.10, 0, 'student12.jpg', 'linkedin.com/in/rohanjoshi', 'github.com/rohanjoshi'),
('AP23110011352', SHA2('03-06-2001', 256), 'Meera Krishnan', 'meera.k@srm.edu.in', 'meerakrishnan@gmail.com', '9876543222', '2001-06-03', 'Information Technology', 'B.Tech', 3, 8.90, 0, 'student13.jpg', 'linkedin.com/in/meerakrishnan', 'github.com/meerakrishnan'),
('AP23110011353', SHA2('16-07-2000', 256), 'Aditya Verma', 'aditya.v@srm.edu.in', 'adityaverma@gmail.com', '9876543223', '2000-07-16', 'Computer Science Engineering', 'B.Tech', 3, 8.20, 0, 'student14.jpg', 'linkedin.com/in/adityaverma', 'github.com/adityaverma'),
('AP23110011354', SHA2('29-04-1999', 256), 'Kavya Rao', 'kavya.r@srm.edu.in', 'kavyarao@gmail.com', '9876543224', '1999-04-29', 'Computer Science Engineering', 'B.Tech', 3, 8.70, 0, 'student15.jpg', 'linkedin.com/in/kavyarao', 'github.com/kavyarao'),
('AP23110011355', SHA2('11-11-2000', 256), 'Siddharth Patel', 'siddharth.p@srm.edu.in', 'siddharthpatel@gmail.com', '9876543225', '2000-11-11', 'Electronics Engineering', 'B.Tech', 3, 7.60, 2, 'student16.jpg', 'linkedin.com/in/siddharthpatel', 'github.com/siddharthpatel'),
('AP23110011356', SHA2('23-02-2001', 256), 'Riya Malhotra', 'riya.m@srm.edu.in', 'riyamalhotra@gmail.com', '9876543226', '2001-02-23', 'Electronics Engineering', 'B.Tech', 3, 8.00, 0, 'student17.jpg', 'linkedin.com/in/riyamalhotra', 'github.com/riyamalhotra'),
('AP23110011357', SHA2('07-08-2000', 256), 'Varun Kapoor', 'varun.k@srm.edu.in', 'varunkapoor@gmail.com', '9876543227', '2000-08-07', 'Mechanical Engineering', 'B.Tech', 3, 7.50, 1, 'student18.jpg', 'linkedin.com/in/varunkapoor', 'github.com/varunkapoor'),
('AP23110011358', SHA2('14-05-1999', 256), 'Ishita Sharma', 'ishita.s@srm.edu.in', 'ishitasharma@gmail.com', '9876543228', '1999-05-14', 'Mechanical Engineering', 'B.Tech', 3, 8.85, 0, 'student19.jpg', 'linkedin.com/in/ishitasharma', 'github.com/ishitasharma'),
('AP23110011359', SHA2('02-09-2000', 256), 'Nikhil Mehta', 'nikhil.m@srm.edu.in', 'nikhilmehta@gmail.com', '9876543229', '2000-09-02', 'Civil Engineering', 'B.Tech', 3, 7.95, 0, 'student20.jpg', 'linkedin.com/in/nikhilmehta', 'github.com/nikhilmehta');