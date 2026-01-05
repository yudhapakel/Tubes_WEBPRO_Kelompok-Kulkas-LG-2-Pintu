const express = require('express');
const router = express.Router();
const connection = require('../config/database');

// GET /api/notifications/:userId - Get all notifications for a user
router.get('/:userId', (req, res) => {
    const { userId } = req.params;

    const query = `
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC
  `;

    connection.query(query, [userId], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json(rows);
    });
});

// GET /api/notifications/:userId/unread - Get unread count
router.get('/:userId/unread', (req, res) => {
    const { userId } = req.params;

    const query = `
    SELECT COUNT(*) as count 
    FROM notifications 
    WHERE user_id = ? AND is_read = 0
  `;

    connection.query(query, [userId], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({ count: rows[0].count });
    });
});

// POST /api/notifications - Create new notification
router.post('/', (req, res) => {
    const { user_id, type, title, message, link, data } = req.body;

    const query = `
    INSERT INTO notifications (user_id, type, title, message, link, data, is_read, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
  `;

    connection.query(query, [user_id, type, title, message, link, JSON.stringify(data || {})], function (err, result) {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.status(201).json({
            id: result.insertId,
            message: 'Notification created successfully'
        });
    });
});

// PUT /api/notifications/:id/read - Mark notification as read
router.put('/:id/read', (req, res) => {
    const { id } = req.params;

    const query = `
    UPDATE notifications 
    SET is_read = 1, updated_at = NOW()
    WHERE id = ?
  `;

    connection.query(query, [id], function (err, result) {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            message: 'Notification marked as read',
            changes: result.affectedRows
        });
    });
});

module.exports = router;
