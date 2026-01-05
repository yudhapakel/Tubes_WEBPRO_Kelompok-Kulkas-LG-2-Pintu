const express = require('express');
const router = express.Router();
const connection = require('../config/database');

// GET /api/payments - List all payments
router.get('/', (req, res) => {
    const query = `
    SELECT p.*, i.invoice_number, u.name as user_name
    FROM payments p
    LEFT JOIN invoices i ON p.invoice_id = i.id
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
  `;

    connection.query(query, [], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json(rows);
    });
});

// GET /api/payments/pending - Get pending verification payments
router.get('/pending', (req, res) => {
    const query = `
    SELECT p.*, i.invoice_number, u.name as user_name
    FROM payments p
    LEFT JOIN invoices i ON p.invoice_id = i.id
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.status = 'pending'
    ORDER BY p.created_at DESC
  `;

    connection.query(query, [], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json(rows);
    });
});

// GET /api/payments/:id - Get payment details
router.get('/:id', (req, res) => {
    const { id } = req.params;

    const query = `
    SELECT p.*, i.invoice_number, i.amount as invoice_amount, u.name as user_name, u.email
    FROM payments p
    LEFT JOIN invoices i ON p.invoice_id = i.id
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
  `;

    connection.query(query, [id], (err, rows) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        if (rows.length === 0) {
            return res.status(404).json({ error: 'Payment not found' });
        }
        res.json(rows[0]);
    });
});

// POST /api/payments/webhook - Payment webhook handler (untuk integrasi payment gateway di masa depan)
router.post('/webhook', (req, res) => {
    // Placeholder untuk webhook dari payment gateway
    console.log('📨 Payment webhook received:', req.body);

    // Di sini bisa diimplementasikan logika untuk:
    // 1. Verify webhook signature
    // 2. Update payment status
    // 3. Send notification to user

    res.json({
        message: 'Webhook received',
        status: 'success'
    });
});

module.exports = router;
