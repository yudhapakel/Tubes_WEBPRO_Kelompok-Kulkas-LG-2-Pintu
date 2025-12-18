@extends('layouts.main') 
@section('title', 'Dashboard')

@section('content')
<main>

    <section class="hero-section">
        <div class="hero-text">
            <h2>Halo, {{ Auth::user()->name }}!</h2>
            <p>Role Anda: {{ ucfirst(Auth::user()->role) }}</p>
            <p>#TrilokaTepat</p>
        </div>
    </section>

    <section class="content-section">
        <h2>Menghadirkan solusi untuk menghadapi tantangan sehari-hari</h2>

        <div class="card-container">
            <h3>Skala kami</h3>

            <div class="card-grid">
                <div class="info-card" id="card-1">
                    <div class="card-text">Perusahaan berbasis proyek</div>
                </div>
                <div class="info-card" id="card-2">
                    <div class="card-text">Fokus pada efisiensi dan pertumbuhan digital</div>
                </div>
                <div class="info-card" id="card-3">
                    <div class="card-text">Mitra Tepercaya Perusahaan Swasta</div>
                </div>
            </div>


            @guest
                <div style="margin-top: 30px;">
                    <a href="{{ route('register') }}" class="cta-button" style="text-decoration: none;">Gabung</a>
                </div>
            @endguest


        </div>
    </section>

</main>
@endsection