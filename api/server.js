const express = require('express');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors()); // Enable CORS untuk Laravel
app.use(express.json()); // Parse JSON request body
app.use(express.urlencoded({ extended: true })); // Parse URL-encoded bodies

// Import routes
const healthRoutes = require('./routes/health');
const notificationRoutes = require('./routes/notifications');
const paymentRoutes = require('./routes/payments');
const documentRoutes = require('./routes/documents');

// Mount routes
app.use('/api/health', healthRoutes);
app.use('/api/notifications', notificationRoutes);
app.use('/api/payments', paymentRoutes);
app.use('/api/documents', documentRoutes);

// Root endpoint
app.get('/', (req, res) => {
    res.json({
        message: 'Node.js API untuk Laravel',
        version: '1.0.0',
        endpoints: {
            health: '/api/health',
            notifications: '/api/notifications',
            payments: '/api/payments',
            documents: '/api/documents'
        }
    });
});

// 404 handler
app.use((req, res) => {
    res.status(404).json({
        error: 'Endpoint not found',
        path: req.path
    });
});

// Error handler
app.use((err, req, res, next) => {
    console.error('❌ Error:', err);
    res.status(500).json({
        error: 'Internal server error',
        message: err.message
    });
});

// Start server
app.listen(PORT, () => {
    console.log('╔══════════════════════════════════════════╗');
    console.log(`║  🚀 Node.js API running on port ${PORT}   ║`);
    console.log('╠══════════════════════════════════════════╣');
    console.log(`║  📍 http://localhost:${PORT}              ║`);
    console.log(`║  ❤️  Health: http://localhost:${PORT}/api/health  ║`);
    console.log('╚══════════════════════════════════════════╝');
});

module.exports = app;
