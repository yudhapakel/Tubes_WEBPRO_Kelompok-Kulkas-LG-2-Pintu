const mysql = require('mysql2');
require('dotenv').config();

// Create MySQL connection untuk Laragon
const connection = mysql.createConnection({
    host: process.env.DB_HOST || 'localhost',
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USERNAME || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_DATABASE || 'tubes_webpro'  // Updated to match your database name
});

// Connect ke database
connection.connect((err) => {
    if (err) {
        console.error('❌ Error connecting to MySQL database:', err.message);
        process.exit(1);
    }
    console.log('✅ Connected to MySQL database:', process.env.DB_DATABASE || 'tubes_webpro');
});

module.exports = connection;
