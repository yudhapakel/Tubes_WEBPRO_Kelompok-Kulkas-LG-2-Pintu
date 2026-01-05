const express = require('express');
const router = express.Router();
const connection = require('../config/database');

// GET /api/documents/stats - Get document statistics
router.get('/stats', (req, res) => {
    const query = `
    SELECT 
      status,
      COUNT(*) as count
    FROM documents
    GROUP BY status
  `;

    connection.query(query, [], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }

        // Format response
        const stats = {
            total: 0,
            pending: 0,
            reviewed: 0,
            accepted: 0,
            rejected: 0
        };

        rows.forEach(row => {
            stats[row.status] = row.count;
            stats.total += row.count;
        });

        res.json(stats);
    });
});

// GET /api/documents/recent - Get recently uploaded documents
router.get('/recent', (req, res) => {
    const limit = req.query.limit || 10;

    const query = `
    SELECT d.*, u.name as user_name, u.email
    FROM documents d
    LEFT JOIN users u ON d.user_id = u.id
    ORDER BY d.created_at DESC
    LIMIT ?
  `;

    connection.query(query, [limit], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json(rows);
    });
});

// GET /api/documents/:id - Get document details
router.get('/:id', (req, res) => {
    const { id } = req.params;

    const query = `
    SELECT d.*, u.name as user_name, u.email
    FROM documents d
    LEFT JOIN users u ON d.user_id = u.id
    WHERE d.id = ?
  `;

    connection.query(query, [id], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        if (rows.length === 0) {
            return res.status(404).json({ error: 'Document not found' });
        }
        res.json(rows[0]);
    });
});

module.exports = router;
