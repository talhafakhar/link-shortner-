# 🚀 Laravel URL Shortener

## 📌 Project Overview
Laravel URL Shortener is a powerful web application that allows users to convert long URLs into short, shareable links. It supports custom slugs, analytics tracking, and expiration handling, making it a feature-rich and user-friendly tool for managing URLs.

---

## ✨ Features

### 🎯 Core Functionality
- 🔗 Generate shortened URLs from long URLs
- ✏️ Create custom URL slugs
- ⏳ Set expiration periods for shortened links
- 📊 Track URL visit analytics (total visits, timestamps, IP logs)
- ❌ Prevent duplicate slugs
- ⚡ Efficient redirection with caching

### 🛠️ Technical Capabilities
- ✅ Clean and modular code architecture
- 🔍 Comprehensive testing suite
- 🚀 Performance optimization with caching
- ⛔ Rate limiting to prevent abuse
- ⚙️ Detailed error handling and validation

---

## ⚙️ Prerequisites
Ensure you have the following installed before starting:

- ✅ **PHP 8.1+**
- ✅ **Composer**
- ✅ **Laravel 10.x**
- ✅ **MySQL or PostgreSQL** (for database storage)
- ✅ **Node.js & npm** (for frontend assets)

---

## 📥 Installation

1️⃣ Clone the repository:
```bash
 git clone https://github.com/talhafakhar/link-shortner-.git
 cd url-shortener
```

2️⃣ Install PHP dependencies:
```bash
composer install
```

3️⃣ Set up environment variables:
```bash
cp .env.example .env
php artisan key:generate
```

4️⃣ Configure database in `.env` file

5️⃣ Run database migrations:
```bash
php artisan migrate
```

6️⃣ Install and compile frontend assets:
```bash
npm install
npm run dev
```

7️⃣ Start the development server:
```bash
php artisan serve
```

---

## 🚀 Usage

### 🔗 Creating Short URLs
1. Navigate to the home page
2. Enter a long URL
3. *(Optional)* Specify a custom slug and/or set an expiration date
4. Click **"Shorten URL"**
5. Copy and share your shortened link

### 📊 Viewing Analytics
1. Click **"View Analytics"** after creating a short URL
2. View total visits, creation date, and recent visit details

---

## 🧪 Testing
Run the comprehensive test suite:
```bash
php artisan test
```

### 🛠️ Test Coverage
- ✅ **Unit tests** for URL shortening service
- ✅ **Feature tests** for controllers
- ✅ **Integration tests** for API endpoints

---

## 📂 Project Structure
```
app/
├── Controllers/
├── Models/
├── Services/
│   ├── UrlShortenerService.php
│   └── AnalyticsService.php
├── Http/
│   ├── Requests/
│   └── Middleware/
tests/
│   ├── Unit/
│   └── Feature/
resources/
│   └── views/
routes/
```

---

## ⚡ Performance Optimizations
- 🚀 **Caching for URL redirections**
- ⛔ **Rate limiting to prevent abuse**
- 🔄 **Optimized database queries**

---

## 🔒 Security Features
- 🛡️ **Input validation** to prevent invalid URLs
- 🔑 **Unique slug generation** to avoid conflicts
- ⛔ **Rate limiting** to prevent abuse
- ⚠️ **Proper error handling** for stability

---

## 📅 Future Roadmap
🚀 Planned features for future releases:
- 🔐 **User authentication** for managing URLs
- 📑 **Bulk URL shortening**
- 📊 **Enhanced analytics dashboard**
- 🛠️ **API support for developers**

---

## 🤝 Contributing
We welcome contributions! Follow these steps:
1. **Fork the repository**
2. **Create your feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit your changes** (`git commit -m 'Add some AmazingFeature'`)
4. **Push to the branch** (`git push origin feature/AmazingFeature`)
5. **Open a Pull Request** 🚀

---

## 📜 License
This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

## 📬 Contact
📧 For any questions, reach out at **ku5752750@gmail.com**

---

This README provides a comprehensive guide to setting up, using, and contributing to the Laravel URL Shortener. Happy coding! 🚀

