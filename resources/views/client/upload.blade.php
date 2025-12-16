@extends('layouts.main')
@section('title', 'Upload Dokumen')

@section('content')
<link rel="stylesheet" href="{{ asset('css/upload.css') }}">

<main class="wrap main" id="content" style="padding-top: 20px;">
    
@if(session('show_modal'))
    <div class="modal-overlay" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6); /* Gelap transparan */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999 !important; /* Paling depan sedunia */
        backdrop-filter: blur(2px); /* Efek blur dikit */
    ">
        
        <div class="modal-card" style="
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            animation: popIn 0.3s ease-out;
            position: relative;
        ">
            
            <div style="margin-bottom: 20px;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 80px; height: 80px; margin: 0 auto; display: block;">
                    <circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2" fill="#d1fae5"/>
                    <path d="M7 12.5l3 3 7-7" stroke="#15803d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h2 style="font-size: 24px; font-weight: bold; color: #111; margin-bottom: 10px;">Upload Berhasil</h2>
            
            <p style="color: #666; margin-bottom: 25px; font-size: 14px;">
                Dokumen kamu telah tersimpan aman.
            </p>

            <a href="{{ url('/dashboard') }}" style="
                display: inline-block;
                background-color: #10b981;
                color: white;
                padding: 12px 30px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: 0.3s;
                border: none;
                cursor: pointer;
            ">
                Kembali ke Dashboard
            </a>

        </div>
    </div>

    <style>
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
@endif

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p>Mohon lengkapi semua data!</p>
        </div>
    @endif

    <h1>Isi formulir ini</h1>

    <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" id="form">
        @csrf 

        <div class="grid" role="region" aria-label="Form upload dokumen">
            
            <div class="left-section">
                <div class="field">
                    <label class="label" for="nama">Nama lengkap</label>
                    <input class="input" id="nama" name="nama" value="{{ Auth::user()->name }}" readonly style="background-color: #f3f4f6;" />
                </div>

                <div class="field">
                    <label class="label" for="alamat">Alamat</label>
                    <textarea class="textarea" id="alamat" name="alamat" placeholder="Nama jalan, RT/RW, dsb" required></textarea>
                </div>

                <div class="field">
                    <label class="label" for="telepon">Nomor telepon</label>
                    <input class="input" id="telepon" name="telepon" type="tel" placeholder="08xxxxxxxxxx" required />
                </div>

                <div class="field">
                    <label class="label" for="jasa">Jasa yang dipilih</label>
                    <div class="select-wrap">
                        <select class="select" id="jasa" name="jasa" required>
                            <option value="" disabled selected>Pilih jasa</option>
                            <option value="Impor barang">Impor barang</option>
                            <option value="Jasa gudang">Jasa gudang</option>
                            <option value="Jasa kurir">Jasa kurir</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label class="label" for="kota">Kota</label>
                        <input class="input" id="kota" name="kota" placeholder="Kota" required />
                    </div>
                    <div class="field">
                        <label class="label" for="prov">Provinsi</label>
                        <div class="select-wrap">
                            <select class="select" id="prov" name="prov" required>
                                <option value="" disabled selected>Pilih provinsi</option>
                                <option>Jawa Barat</option>
                                <option>DKI Jakarta</option>
                                <option>Jawa Tengah</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label class="label" for="negara">Negara</label>
                        <div class="select-wrap">
                            <select class="select" id="negara" name="negara" required>
                                <option value="" disabled selected>Pilih negara</option>
                                <option>Indonesia</option>
                                <option>Malaysia</option>
                                <option>Singapura</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="kodepos">Kode pos</label>
                        <input class="input" id="kodepos" name="kodepos" inputmode="numeric" placeholder="5 digit" required />
                    </div>
                </div>
            </div>

            <aside class="upload-right">
                <div class="card">
                    <h3 style="margin:0 0 10px">Unggah dokumen anda</h3>

                    <div class="drop" id="drop">
                        <input type="file" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" style="display: block; margin: 20px auto;" required />
                        <p class="hint">* Hanya .pdf/.jpg/.png. Maks 5 MB per file.</p>
                    </div>

                    <label class="terms">
                        <input type="checkbox" id="agree" required />
                        <span>Saya setuju dengan <a href="#">Syarat dan ketentuan</a></span>
                    </label>

                    <div class="actions">
                        <button type="submit" class="btn btn--success" style="cursor: pointer;">Submit</button>
                        <a href="{{ url('/dashboard') }}" class="btn btn--dark" style="text-decoration: none; text-align: center;">Cancel</a>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</main>
@endsection