# 🎓 Placement Portal

A comprehensive web-based placement management system for college students and administrators. This portal streamlines the process of job notifications, application tracking, student profile validation, and company visit scheduling.

---

## 🚀 Deployment (Demo)

🖥 Live Demo- placementportal.42web.io

Copy Paste it in url section and click on continue to site and click on Details and down you can see a link called this unsafe site click on that You can access to my website



---

## 📂 Project Structure

```
placement-portal/
│
├── index.php                   # Landing page
├── home.php                    # Student home/dashboard
├── config.php                  # Database connection
├── functions.php               # Reusable backend functions
├── db_setup.php                # Database schema creation
├── add_job_notification.php    # Admin: Add job openings
├── add_notification.php        # Admin: Post general notifications
├── check_cgpa.php              # Eligibility validation logic
├── applications.php            # Track job applications
├── admin-attendance.php        # Admin: Attendance log
├── image.png                   # Portal banner/logo
└── *.sql                       # Database scripts
```

---

## 📌 Features

- ✅ **Student Authentication & Profile**
- 📢 **Job & Notification Posting**
- 📝 **Eligibility Check Based on CGPA**
- 📄 **Application Tracking**
- 📊 **Student Attendance tracking details**

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL

---

## ⚙️ How to Run Locally

1. **Clone the Repository**

```bash
git clone https://github.com/your-username/placement-portal.git
cd placement-portal
```

2. **Start XAMPP / WAMP Server**

3. **Move Files to `htdocs` Folder**

Copy all files to:

```
C:\xampp\htdocs\placement-portal
```

4. **Import Database**

- Open `phpMyAdmin`
- Create a database named `placement`
- Import the following files in order:
  - `db_setup.php`
  - `additional_jobs.sql`

5. **Access in Browser**

```
http://localhost/placement-portal/index.php
```

---

## 🧪 Dummy Credentials

| Role         | Username     | Password |
|--------------|--------------|----------|
| Student      | AP23110011343|05-03-2001|

> You can modify or add users directly in the database.

---



---

## 📄 License

This project is licensed under the **MIT License** – feel free to use and adapt.

---

## 🤝 Contributions

Feel free to fork the repository and submit pull requests! For major changes, open an issue first to discuss what you would like to change.


## ✍️ Author

**Mounika Chowdary**  
🎓 B.Tech CSE Student, SRM University AP  
📧 Email: mounikachowdarys2807@gmail.com  
🌐 GitHub: [github.com/Mounika-Chowdary28](https://github.com/Mounika-Chowdary28)  
📍 India
