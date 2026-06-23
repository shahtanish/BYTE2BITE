# 🍔 BYTE2BITE - Multi-Vendor Food Ordering System

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8+-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-Frontend-purple)

## 📌 Overview

BYTE2BITE is a full-stack Multi-Vendor Food Ordering Platform that connects customers, restaurants, delivery partners, and administrators on a single system.

The platform allows customers to browse multiple restaurants, place orders, track deliveries in real-time, and download invoices. Restaurant owners can manage menus and orders, delivery partners can handle deliveries and earnings, while administrators monitor and control the complete ecosystem.

---

## 🚀 Features

### 👨‍💼 Admin Panel

* Restaurant Approval System
* Delivery Partner Approval
* Customer Management
* Order Monitoring
* Revenue Analytics
* Food Category Management
* Reports Dashboard

### 🍽 Restaurant Panel

* Restaurant Dashboard
* Menu Management
* Add/Edit/Delete Food Items
* Order Acceptance & Rejection
* Sales Reports
* Profile Management
* Availability Control

### 🚚 Delivery Partner Panel

* Available Orders Dashboard
* Order Pickup Management
* Delivery Tracking
* Earnings Dashboard
* Profile Management

### 👤 Customer Panel

* User Registration & Login
* Browse Restaurants
* Multi-Restaurant Cart
* Order Placement
* Live Order Tracking
* Invoice Generation
* Order History

---

## 🏗 System Architecture

Customer
↓
Restaurant
↓
Delivery Partner
↓
Admin Dashboard

All modules communicate through a centralized Laravel backend and MySQL database.

---

## 🛠 Tech Stack

### Frontend

* HTML5
* CSS3
* Bootstrap
* JavaScript
* Blade Templates

### Backend

* Laravel
* PHP

### Database

* MySQL

### Development Tools

* Composer
* Git
* GitHub

---

## ✨ Key Highlights

* Multi-Vendor Architecture
* Role-Based Access Control
* Order Lifecycle Management
* Revenue Reporting
* Invoice Generation
* Live Delivery Tracking
* Responsive Design

---

## 📊 Order Flow

Customer Places Order
↓
Restaurant Accepts Order
↓
Preparing
↓
Ready for Pickup
↓
Delivery Partner Picks Up
↓
On The Way
↓
Delivered

---


## ⚙ Installation

### Clone Repository

```bash
git clone https://github.com/shahtanish/BYTE2BITE.git
cd BYTE2BITE
```

### Install Dependencies

```bash
composer install
```

### Configure Environment

```bash
cp .env.example .env
```

Update database credentials inside `.env`

### Generate Application Key

```bash
php artisan key:generate
```

### Run Migrations

```bash
php artisan migrate --seed
```

### Start Server

```bash
php artisan serve
```

---

## 👨‍💻 Demo Credentials

### Admin

Email:
[admin@byte2bite.com](mailto:admin@byte2bite.com)

Password:
admin123

### Restaurant

Email:
[spice@byte2bite.com](mailto:spice@byte2bite.com)

Password:
restaurant123

### Delivery Partner

Email:
[raj@byte2bite.com](mailto:raj@byte2bite.com)

Password:
delivery123

### Customer

Email:
[aarav@example.com](mailto:aarav@example.com)

Password:
customer123

---

## 🎯 Learning Outcomes

This project helped me gain practical experience in:

* Full Stack Development
* Laravel Framework
* Database Design
* Authentication & Authorization
* MVC Architecture
* CRUD Operations
* Dashboard Development
* Software Project Deployment

---

## 📈 Future Enhancements

* AI Food Recommendation System
* Online Payment Gateway Integration
* Push Notifications
* Mobile Application
* Advanced Analytics Dashboard
* GPS-Based Delivery Tracking
* Coupon & Offers System

---

## 👤 Author

**Shah Tanish**


GitHub:
https://github.com/shahtanish

---

⭐ If you found this project interesting, don't forget to star the repository.
