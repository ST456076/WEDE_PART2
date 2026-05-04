# ClothingStore Web Application

## Introduction
The ClothingStore Web Application is a web-based system developed to manage customer data and basic store operations efficiently. The system provides an administrative interface where an admin can log in and perform various actions such as adding, editing, viewing, and deleting customer records.

This project was developed as part of an academic assignment to demonstrate understanding of web development concepts, database integration, and CRUD operations.

## Objectives
The main objectives of this project are:
- To develop a functional web application using PHP and MySQL  
- To implement CRUD (Create, Read, Update, Delete) operations  
- To manage customer records efficiently  
- To demonstrate authentication and session management  
- To design a simple and user-friendly interface  

## Features

### Authentication System
- Admin login functionality  
- Session-based authentication  
- Secure access to admin pages  
- Logout functionality  

### Customer Management
- Add new customers  
- View all customers in a table format  
- Edit existing customer details  
- Delete customer records  

### User Interface
- Clean and simple design  
- Easy navigation between pages  
- Styled using CSS  

## Technologies Used

Frontend:
- HTML  
- CSS  

Backend:
- PHP  

Database:
- MySQL  

Development Environment:
- XAMPP / WAMP  

## System Architecture
The system follows a basic client-server architecture:
- The client interacts through a web browser  
- The server processes requests using PHP  
- Data is stored and retrieved from a MySQL database  

## Project Structure
ClothingStore/  
├── login.php            # User login page  
├── admin_login.php      # Admin authentication  
├── user_dashboard.php   # User dashboard  
├── customers.php        # Display all customers  
├── add_customer.php     # Add new customer  
├── edit_customer.php    # Update customer details  
├── delete_customer.php  # Delete customer  
├── DBconn.php           # Database connection  
├── style.css            # Styling file  
└── logout.php           # Logout functionality  

## Database Design
The system uses a MySQL database to store customer and admin data.

Tables:

tbladmin:
- admin_id  
- admin_full_name  
- email  
- password  

tbluser:
- user_id  
- full_name  
- email  

## Installation and Setup
To run the project locally:

1. Install XAMPP or WAMP server  
2. Place the project folder inside:
   - htdocs (XAMPP)  
   - www (WAMP)  
3. Start Apache and MySQL from the control panel  
4. Open phpMyAdmin and create a new database  
5. Import the SQL file (if provided)  
6. Update database connection in DBconn.php  
7. Run the project in your browser:
   http://localhost/ClothingStore  

## How to Use the System
- Open the application in a browser  
- Log in using admin credentials  
- Access the customer management dashboard  
- Perform actions:
  - Add customer  
  - View customers  
  - Edit customer details  
  - Delete customers  
- Log out when done  

## Challenges Faced
- Connecting PHP to MySQL database  
- Handling sessions for login authentication  
- Debugging SQL errors  
- Managing file structure and navigation  

## Future Improvements
- Add user registration system  
- Improve UI/UX design  
- Add search and filter functionality  
- Implement password encryption  
- Deploy the system online  

## Conclusion
This project demonstrates the implementation of a basic web application with full CRUD functionality and authentication. It highlights fundamental concepts in web development and database management.

## Author
Name: Thobeka Sithole, Ntokozo Mashiane  
Project: ClothingStore Web Application  
Year: 2026  

## License
This project is for educational purposes only.
