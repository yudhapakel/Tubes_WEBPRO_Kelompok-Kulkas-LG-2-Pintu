<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function create($userId, $type, $title, $message, $link = null, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    // Notification types and templates
    public static function quotationSent($clientId, $quotationId, $quotationNumber)
    {
        return self::create(
            $clientId,
            'quotation_sent',
            'Penawaran Baru Tersedia',
            "Admin telah mengirimkan penawaran #{$quotationNumber} untuk Anda. Silakan review dan berikan tanggapan.",
            "/payment",
            ['quotation_id' => $quotationId]
        );
    }

    public static function quotationNegotiated($adminId, $quotationId, $quotationNumber, $clientName)
    {
        return self::create(
            $adminId,
            'quotation_negotiated',
            'Klien Mengajukan Negosiasi',
            "{$clientName} mengajukan counter offer untuk penawaran #{$quotationNumber}. Silakan review.",
            "/admin/penawarans/{$quotationId}",
            ['quotation_id' => $quotationId]
        );
    }

    public static function quotationAccepted($adminId, $quotationId, $quotationNumber, $clientName)
    {
        return self::create(
            $adminId,
            'quotation_accepted',
            'Penawaran Diterima',
            "{$clientName} menerima penawaran #{$quotationNumber}. Silakan convert ke invoice.",
            "/admin/penawarans/{$quotationId}",
            ['quotation_id' => $quotationId]
        );
    }

    public static function invoiceCreated($clientId, $invoiceId, $invoiceNumber)
    {
        return self::create(
            $clientId,
            'invoice_created',
            'Invoice Siap Dibayar',
            "Invoice #{$invoiceNumber} telah dibuat. Silakan lakukan pembayaran.",
            "/payment",
            ['invoice_id' => $invoiceId]
        );
    }

    public static function paymentUploaded($adminId, $paymentId, $invoiceNumber, $clientName)
    {
        return self::create(
            $adminId,
            'payment_uploaded',
            'Bukti Pembayaran Diterima',
            "{$clientName} telah upload bukti pembayaran untuk invoice #{$invoiceNumber}. Silakan verifikasi.",
            "/admin/payments/{$paymentId}",
            ['payment_id' => $paymentId]
        );
    }

    public static function paymentVerified($clientId, $invoiceId, $invoiceNumber)
    {
        return self::create(
            $clientId,
            'payment_verified',
            'Pembayaran Terverifikasi',
            "Pembayaran Anda untuk invoice #{$invoiceNumber} telah diverifikasi. Terima kasih!",
            "/invoice/{$invoiceId}",
            ['invoice_id' => $invoiceId]
        );
    }

    public static function paymentRejected($clientId, $paymentId, $invoiceNumber, $reason)
    {
        return self::create(
            $clientId,
            'payment_rejected',
            'Pembayaran Ditolak',
            "Pembayaran Anda untuk invoice #{$invoiceNumber} ditolak. Alasan: {$reason}",
            "/payment/pay/{$paymentId}",
            ['payment_id' => $paymentId]
        );
    }

    public static function adminCounterOffer($clientId, $quotationId, $quotationNumber, $amount)
    {
        return self::create(
            $clientId,
            'admin_counter_offer',
            'Balasan Negosiasi dari Admin',
            "Admin membalas negosiasi Anda untuk penawaran #{$quotationNumber} dengan harga Rp " . number_format($amount, 0, ',', '.'),
            "/payment",
            ['quotation_id' => $quotationId]
        );
    }

    public static function newRequest($adminId, $documentId, $clientName, $service)
    {
        return self::create(
            $adminId,
            'new_request',
            'Request Baru dari Klien',
            "{$clientName} mengajukan request untuk {$service}. Silakan review dan buat penawaran.",
            "/admin/requests/{$documentId}",
            ['document_id' => $documentId]
        );
    }
}
