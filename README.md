 FixerUpper E-Commerce Prototype

A secure e-commerce web application built for FixerUpper, a hardware appliance retailer transitioning from a brick-and-mortar model to an online store.
The prototype demonstrates product display, shopping cart, user authentication, and security best practices using PHP and MySQL.

🚀 Features

🛒 Product listing with category filtering

👤 User registration & login (authentication)

🧺 Add to cart & remove from cart functionality

💳 Simulated checkout process

🔐 SQL Injection prevention & secure password hashing


🧰 Technologies Used

Frontend:

HTML5

CSS3 (with responsive layout)

JavaScript (for dynamic UI updates)

Backend:

PHP (Core PHP, no frameworks)

MySQL Database

Tools:

XAMPP / WAMP (Localhost server)

phpMyAdmin (for database management)

Visual Studio Code

Git & GitHub for version control

🗂️ Project Structure
FixerUpper-E-commerce-Prototype/
│
├── index.php               # Homepage (product display)
├── product.php             # Product details page
├── cart.php                # Shopping cart page
├── checkout.php            # Checkout simulation
├── login.php               # User login
├── register.php            # User registration
├── config/
│   └── database.php        # Database connection file
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── schema.sql          # Database structure
└── README.md

⚙️ Setup Instructions

Clone the Repository:

git clone https://github.com/dorinahabravan/FixerUpper-E-commerce-Prototype.git


Move the project to your local server folder (e.g. htdocs for XAMPP).

Create the database:

Open http://localhost/phpmyadmin

Create a new database called fixerupper_db

Import database/schema.sql

Run the project:

Visit http://localhost/FixerUpper-E-commerce-Prototype/index.php

🧑‍💻 Author

Dorina Habravan


🏷️ Tags

#PHP #MySQL #WebDevelopment #Ecommerce #SecureCoding
#FrontendDevelopment #BackendDevelopment #FullStack #SoftwareProject #FixerUpper
