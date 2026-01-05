const express = require('express');
const router = express.Router();
const connection = require('../config/database');

// GET /api/health - Health check endpoint
router.get('/', (req, res) => {
    // Test database connection
    connection.query('SELECT 1 as test', [], (err, rows) => {
        if (err) {
            return res.status(500).json({
                status: 'error',
                message: 'Database connection failed',
                error: err.message
            });
        }

        res.json({
            status: 'ok',
            message: 'API is running',
            database: 'connected',
            timestamp: new Date().toISOString()
        });
    });
});

module.exports = router;
