@extends('layouts.main')
@section('title', 'Pembayaran Tagihan')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">

    <div class="title" style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">
        Pilih Metode Pembayaran
    </div>

    @if($invoice)

    <form action="{{ route('payment.process', $invoice->id) }}" method="POST">
        @csrf

        <div class="payment-wrapper" style="display: flex; gap: 30px; flex-wrap: wrap;">

            <div class="payment-method" style="flex: 2; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px;">💳 Credit/Debit Card</h3>

                <input type="hidden" name="payment_method" value="Credit Card">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nomor Rekening</label>
                    <input type="text" name="card_number" placeholder="XXX-XXXX-XXXXX-XXXX" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Bank</label>
                        <input type="text" name="bank_name" placeholder="BCA" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Berlaku Sampai</label>
                        <input type="month" name="expired_date" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <div class="other-methods" style="margin-top: 25px;">
                    <p><strong>Metode Pembayaran Lain:</strong></p>
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard" width="50">
                        <img src="https://seeklogo.com/images/V/visa-logo-6F4057663D-seeklogo.com.png" alt="Visa" width="50">
                        <div style="width: 50px; height: 30px; background: #00529C; display: flex; align-items: center; justify-content: center; color: white; border-radius: 4px; font-weight: bold; font-size: 10px;">BCA</div>
                    </div>
                </div>
            </div>

            <div class="payment-detail" style="flex: 1; background: #f8f9fa; padding: 30px; border-radius: 12px; height: fit-content; border: 1px solid #eee;">
                <h3 style="margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Detail Pembayaran</h3>

                <div class="detail-box" style="margin-bottom: 20px;">
                    <p style="margin-bottom: 5px;"><strong>Jasa:</strong> <br> {{ $invoice->service_name }}</p>
                    <p style="margin-bottom: 5px; font-size: 18px; color: #2563eb;">
                        <strong>Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                    </p>
                </div>

                <hr>

                <div class="detail-box" style="margin-bottom: 25px; font-size: 14px; color: #555;">
                    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Alamat:</strong> {{ Auth::user()->address ?? 'Alamat belum diisi' }}</p>
                </div>

                <button type="submit" class="btn-pay" style="width: 100%; padding: 15px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s;">
                    Bayar Sekarang
                </button>
            </div>

        </div>
    </form>

    @else

    <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        <div style="width: 80px; height: 80px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>

        <h2 style="color: #2c3e50; margin-bottom: 10px;">Semua Beres! 🎉</h2>
        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
            Tidak ada tagihan yang perlu dibayar saat ini.<br>
            Terima kasih sudah menjadi klien setia Triloka Sejahtera.
        </p>

        <a href="{{ url('/dashboard') }}" style="text-decoration: none; background: #2c3e50; color: white; padding: 12px 30px; border-radius: 8px; font-weight: bold; transition: 0.3s;">
            Kembali ke Dashboard
        </a>
    </div>

    @endif

</div>
@endsection