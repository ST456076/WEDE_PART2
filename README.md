# ClothingStore Web Application

## Introduction
The ClothingStore Web Application is a web-based system developed to manage customer data and a peer-to-peer clothing marketplace. The system provides both user and admin interfaces where users can browse and list clothing items, and administrators can manage customers and listings.

This project was developed as part of an academic assignment to demonstrate understanding of web development concepts, database integration, authentication, and CRUD operations.

### Objectives
The main objectives of this project are:
To develop a functional web application using PHP and MySQL
To implement CRUD (Create, Read, Update, Delete) operations
To build a simple e-commerce-style clothing marketplace
To demonstrate authentication and session management
To design a clean and user-friendly interface

### Features
Authentication System
User login functionality
Admin login functionality
Session-based authentication
Secure access to dashboards
Logout functionality
User Marketplace Features
Browse clothing items
Filter items by category (Women, Men, Kids Clothes, Accessories, Bags, Shoes)
Add items to cart
Save items to wishlist
Add new clothing listings
View personal listings
Admin Dashboard (NEW)
View total users, listings, cart items, and wishlist items
View all registered users
Delete users (including their listings, cart, and wishlist data)
View all marketplace listings
Delete inappropriate or unwanted listings
Navigate between admin panel and marketplace
Customer Management
Add new customers
View all customers
Edit customer details
Delete customers
User Interface
Clean and modern design
Consistent styling across all pages
Responsive layout
Category-based navigation
Styled using CSS
Technologies Used

### Frontend:
HTML
CSS

### Backend:
PHP

### Database:
MySQL

### Development Environment:
WAMP / XAMPP
System Architecture

#### The system follows a client-server architecture:
The client interacts through a web browser
The server processes requests using PHP
Data is stored and retrieved from a MySQL database
Project Structure

ClothingStore/
├── login.php
├── admin_login.php
├── user_dashboard.php
├── admin_dashboard.php # NEW admin panel
├── add_listing.php
├── my_listings.php
├── cart.php
├── wishlist.php
├── customers.php
├── add_customer.php
├── edit_customer.php
├── delete_customer.php
├── DBconn.php
├── bootstrap.php
├── style.css
├── logout.php
└── images/ # product images

## Database Design

Tables:
tbladmin
admin_id
admin_full_name
email
password
tbluser
user_id
full_name
email
listings
listing_id
user_id
title
description
size
category
condition_status
price
image_url
created_at
cart
id
user_id
listing_id
quantity
wishlist
id
user_id
listing_id
Admin Login Credentials

### To access the admin dashboard:
Email:
admin@gmail.com
Password:
admin123

## Installation and Setup
Install WAMP or XAMPP
Place the project folder inside:
www (WAMP)
htdocs (XAMPP)
Start Apache and MySQL
Open phpMyAdmin

### Create a database named:
clothingstore
Import the SQL file (if provided)
Update database connection in DBconn.php if needed

### Run the project:
http://localhost/ClothingStore

### How to Use the System
User
Log in
Browse clothing items
Filter by category
Add items to cart or wishlist
Add new listings
Admin
Log in using admin credentials
View dashboard statistics
Manage users
Manage listings
Delete unwanted data
Challenges Faced
Connecting PHP to MySQL database
Handling session authentication
Debugging SQL and PHP errors
Implementing category filtering
Managing dynamic UI updates
Future Improvements
Add user registration system
Implement password hashing for security
Add image upload (instead of URL input)
Improve search functionality
Add order/checkout system
Deploy the system online
Conclusion

This project demonstrates the implementation of a full-stack web application with authentication, CRUD operations, and a basic marketplace system. It highlights core web development and database management skills.

## Authors
Name: Thobeka Sithole, Ntokozo Mashiane
Project: ClothingStore Web Application
Year: 2026

# License
This project is for educational purposes only.
