<nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #1e293b; color: white;">
    <div class="navbar-brand">
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="navbar-brand" style="text-decoration: none; color: white !important; display: flex; align-items: center; gap: 10px;">

            @if(isset($logo))
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 40px;">
            @endif

            <span style="font-weight: bold; font-size: 1.2rem;">CV. TRILOKA SEJAHTERA</span>

        </a>
    </div>

    <ul class="navbar-nav" style="display: flex; list-style: none; gap: 20px; margin: 0; padding: 0;">
        <li><a href="/payment" style="color: white; text-decoration: none;">Payment</a></li>
        <li><a href="/notification" style="color: white; text-decoration: none;">Notification</a></li>
        <li><a href="/profile" style="color: white; text-decoration: none;">My Profile</a></li>
        <a href="{{ route('upload.create') }}" style="color: white; text-decoration: none;">  Document Upload </a>
    </ul>

    <div class="user-menu" style="display: flex; align-items: center; gap: 15px;">
        <div style="text-align: right;">
            <span style="display: block; font-weight: bold; font-size: 13px;">{{ Auth::user()->name }}</span>
            <span style="display: block; font-size: 11px; color: #94a3b8;">{{ ucfirst(Auth::user()->role) }}</span>
        </div>

        <div class="profile-icon">
            @if(Auth::user()->photo)
            <img src="{{ asset('storage/' . Auth::user()->photo) }}?t={{ time() }}"
                style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid #3b82f6;">
            @else
            <div style="width: 38px; height: 38px; background-color: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            @endif
        </div>

        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="background: transparent; border: 1px solid #f87171; color: #f87171; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                Sign Out
            </button>
        </form>
    </div>
</nav>