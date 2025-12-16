<nav style="background-color: #1f2937; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white;">
    
    <div style="font-weight: bold; font-size: 20px; display: flex; align-items: center; gap: 10px;">
        <img src="{{ asset('src/assets/logo-placeholder.png') }}" alt="Logo" style="height: 30px; filter: brightness(0) invert(1);"> 
        <span>CV.TRILOKA SEJAHTERA</span>
    </div>

    <div style="display: flex; gap: 20px; font-size: 14px;">
        <a href="{{ url('/payment') }}" style="color: white; text-decoration: none;">Payment</a>
        <a href="{{ url('/notification') }}" style="color: white; text-decoration: none;">Notification</a>
        <a href="{{ url('/profile') }}" style="color: white; text-decoration: none;">My Profile</a>
        <a href="{{ url('/upload') }}" style="color: white; text-decoration: none;">Document Upload</a>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        
        <div style="text-align: right; font-size: 12px;">
            <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
            <div style="color: #bbb;">{{ ucfirst(Auth::user()->role) }}</div>
        </div>

        <div style="width: 40px; height: 40px; background-color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;">
            {{ substr(Auth::user()->name, 0, 1) }} </div>

        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="background: none; border: 1px solid #ff4d4d; color: #ff4d4d; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">
                Sign Out
            </button>
        </form>
    </div>
</nav>