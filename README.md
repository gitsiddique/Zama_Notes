# ZAMA-Notes

A **minimalist, secure, and user-friendly** Laravel-based notes app designed for efficiency and personalization. ZAMA-Notes lets you take quick text notes with **auto-sorting, background customization, and seamless CRUD operations**. 

## ✨ Features
- **Create, Edit, Delete Notes** – Manage your notes effortlessly.
- **Auto-Sorting by Date & Time** – Keep recent notes on top.
- **Custom Background Colors** – Choose from **10 beautiful themes**.
- **Fast & Secure** – Built with **Laravel** and **MySQL**.
- **Minimalist UI** – Designed for ease of use and clarity.

## 🛠 Tech Stack
- **Front-end:** HTML, CSS, JavaScript (Minimalistic UI)
- **Back-end:** Laravel (PHP) + MySQL
- **Security:** CSRF protection, authentication mechanisms.

## 🚀 Installation Guide
Follow these steps to install and run ZAMA-Notes on your local machine.

### 1️⃣ Clone the Repository
```sh
 git clone https://github.com/gitsiddique/Zama_Notes.git
```

### 2️⃣ Navigate to the Project Directory
```sh
 cd Zama_Notes
```

### 3️⃣ Install Dependencies
Ensure **Composer** is installed:
```sh
 composer install
```
Install JavaScript dependencies:
```sh
 npm install && npm run dev
```

### 4️⃣ Set Up Environment Variables
Duplicate the `.env` file:
```sh
 cp .env.example .env
```
Generate the application key:
```sh
 php artisan key:generate
```

### 5️⃣ Configure Database
Update the `.env` file with your database credentials:
```env
 DB_DATABASE=zama_notes
 DB_USERNAME=root
 DB_PASSWORD=your_password
```
Run database migrations:
```sh
 php artisan migrate --seed
```

### 6️⃣ Serve the Application
Start the Laravel development server:
```sh
 php artisan serve
```
Now, open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser. 🚀

## 🤝 Contribute & Support
- Found a bug? **Open an issue**
- Want to improve it? **Submit a pull request**
- Like this project? **Give it a ⭐ on GitHub!**

## 📩 Contact
Looking for a **full-time Laravel developer**? Feel free to connect! 

🔗 **GitHub Repository:** [ZAMA-Notes](https://github.com/gitsiddique/Zama_Notes.git)
