# Node.js API untuk Laravel

API server berbasis Node.js yang melengkapi aplikasi Laravel. API ini berbagi database SQLite yang sama dengan Laravel dan menyediakan endpoint untuk notifications, payments, dan documents.

## 🚀 Setup & Installation

### 1. Install Dependencies
```bash
cd api
npm install
```

### 2. Setup Environment
Salin file `.env.example` ke `.env`:
```bash
copy .env.example .env
```

File `.env` sudah dikonfigurasi untuk connect ke database Laravel:
```
PORT=3000
DB_PATH=../database/database.sqlite
NODE_ENV=development
```

### 3. Jalankan Server
```bash
# Development mode (dengan auto-reload)
npm run dev

# Production mode
npm start
```

Server akan berjalan di: **http://localhost:3000**

## 📡 API Endpoints

### Health Check
- `GET /api/health` - Cek status API dan database connection

### Notifications
- `GET /api/notifications/:userId` - Get semua notifikasi user
- `GET /api/notifications/:userId/unread` - Get jumlah notifikasi belum dibaca
- `POST /api/notifications` - Buat notifikasi baru
- `PUT /api/notifications/:id/read` - Tandai notifikasi sudah dibaca

### Payments
- `GET /api/payments` - List semua payments
- `GET /api/payments/pending` - Get payments yang pending verification
- `GET /api/payments/:id` - Detail payment
- `POST /api/payments/webhook` - Webhook handler untuk payment gateway

### Documents
- `GET /api/documents/stats` - Statistik documents berdasarkan status
- `GET /api/documents/recent?limit=10` - Documents yang baru diupload
- `GET /api/documents/:id` - Detail document

## 🧪 Testing API

### Test dengan Browser
Buka browser dan akses:
```
http://localhost:3000/api/health
```

### Test dengan cURL
```bash
# Health check
curl http://localhost:3000/api/health

# Get notifications untuk user id 1
curl http://localhost:3000/api/notifications/1

# Get document statistics
curl http://localhost:3000/api/documents/stats
```

### Test dengan Postman
Import endpoints di atas ke Postman untuk testing yang lebih mudah.

## 🔗 Integrasi dengan Laravel

API ini menggunakan database SQLite yang sama dengan Laravel (`../database/database.sqlite`), jadi semua data real-time dan sinkron.

### Jalankan Laravel + Node.js Bersamaan

**Terminal 1 - Laravel:**
```bash
php artisan serve
```
Runs on: http://localhost:8000

**Terminal 2 - Node.js API:**
```bash
cd api
npm run dev
```
Runs on: http://localhost:3000

### Menggunakan API dari Laravel

Contoh request dari Laravel menggunakan JavaScript:
```javascript
// Get unread notification count
fetch('http://localhost:3000/api/notifications/1/unread')
  .then(res => res.json())
  .then(data => console.log('Unread count:', data.count));
```

## 📁 Project Structure

```
api/
├── config/
│   └── database.js      # Database connection
├── routes/
│   ├── health.js        # Health check routes
│   ├── notifications.js # Notification routes
│   ├── payments.js      # Payment routes
│   └── documents.js     # Document routes
├── .env.example         # Environment template
├── .gitignore          # Git ignore file
├── package.json        # NPM dependencies
├── server.js           # Main server file
└── README.md           # This file
```

## 🛠️ Technologies

- **Express.js** - Web framework
- **SQLite3** - Database driver
- **CORS** - Enable cross-origin requests
- **dotenv** - Environment variables
- **Nodemon** - Development auto-reload

## 📝 Notes

- API berjalan di port **3000** (Laravel di port 8000)
- CORS sudah enabled untuk Laravel
- Database shared dengan Laravel
- Semua responses dalam format JSON

## 🐛 Troubleshooting

### Database connection failed
Pastikan file database ada di path yang benar:
```
../database/database.sqlite
```

### Port already in use
Ubah PORT di file `.env`:
```
PORT=3001
```

### Module not found
Install ulang dependencies:
```bash
npm install
```

---

Made with ❤️ for Laravel Web Programming Project
