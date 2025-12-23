@extends('layouts.main')
@section('title', 'My Profile')

@section('content')
<main style="padding: 40px; max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none; color: #555; display: inline-flex; align-items: center; gap: 5px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #34d399;">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <div class="card" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; height: fit-content;">

            <div id="image-preview-container">
                @if($user->photo)
                <img id="main-preview" src="{{ asset('images/profile/' . $user->photo) }}?t={{ time() }}"
                    alt="Foto Profil"
                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; margin: 0 auto 20px auto; border: 4px solid #f3f4f6; display: block;">
                @else
                <div id="initial-avatar" style="width: 150px; height: 150px; background-color: #2c3e50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 60px; font-weight: bold; margin: 0 auto 20px auto;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <img id="main-preview" src="" alt="Preview"
                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; margin: 0 auto 20px auto; border: 4px solid #f3f4f6; display: none;">
                @endif
            </div>

            <h2 style="margin: 0; font-size: 22px; color: #333;">{{ $user->name }}</h2>
            <p style="color: #666; margin-top: 5px;">{{ ucfirst($user->role) }} Triloka</p>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <div style="text-align: left; font-size: 14px; color: #555;">
                <p style="margin-bottom: 10px;"><strong>Bergabung:</strong> <br> {{ $user->created_at->format('d M Y') }}</p>
                <p><strong>Status:</strong> <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 4px; font-size: 12px;">Active</span></p>
            </div>
        </div>

        <div class="card" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px; margin-bottom: 20px; color: #2c3e50;">
                ✏️ Edit Profil
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #444;">Ganti Foto Profil</label>
                    <input type="file" name="photo" id="photo-input" accept="image/*"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;"
                        onchange="showPreview(this)">
                    <small style="color: #666;">Format: JPG, PNG, JPEG (Maks. 2MB)</small>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #444;">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #444;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #f9fafb;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #444;">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #444;">Alamat Lengkap</label>
                    <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap..."
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">{{ old('address', $user->address) }}</textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" style="background-color: #2563eb; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function showPreview(input) {
        const preview = document.getElementById('main-preview');
        const initial = document.getElementById('initial-avatar');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (initial) {
                    initial.style.display = 'none'; 
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        transition: transform 0.2s;
    }

    button:hover {
        background-color: #1d4ed8 !important;
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection