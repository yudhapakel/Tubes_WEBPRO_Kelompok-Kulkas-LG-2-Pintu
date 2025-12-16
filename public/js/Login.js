// HAPUS BAGIAN CEK LOGIN DI ATAS SINI.
// Biarkan Laravel Middleware (auth) yang ngurus proteksi halaman dashboard.

document.addEventListener("DOMContentLoaded", function() {

    // === ELEMEN-ELEMEN ===
    const loginTab = document.getElementById("login-tab");
    const signupTab = document.getElementById("signup-tab");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const switchToLoginLink = document.getElementById("switch-to-login");
    
    // Password Elements
    const loginPasswordInput = document.getElementById("login-password");
    const loginToggleIcon = document.getElementById("login-toggle-icon");
    const signupPasswordInput = document.getElementById("signup-password");
    const signupToggleIcon = document.getElementById("signup-toggle-icon");
    
    // Path Ikon Mata
    // Pastikan path ini bener sesuai folder public laravel lu.
    // Gw saranin pake '/' di depan biar absolut ke root.
    const eyeOpenIcon = "/src/assets/eye-open.svg"; 
    const eyeClosedIcon = "/src/assets/eye-closed.svg";


    /** Fungsi untuk toggle password (BIARIN INI, INI BAGUS) */
    function setupPasswordToggle(input, icon) {
        if (icon && icon.parentElement) {
            icon.parentElement.addEventListener("click", function() {
                const type = input.getAttribute("type") === "password" ? "text" : "password";
                input.setAttribute("type", type);
                
                if (type === "password") {
                    icon.src = eyeClosedIcon;
                    icon.alt = "Show password";
                } else {
                    icon.src = eyeOpenIcon;
                    icon.alt = "Hide password";
                }
            });
        }
    }

    /** Fungsi beralih ke mode Login (UI LOGIC) */
    function showLoginForm() {
        if (loginTab) loginTab.classList.add("active");
        if (signupTab) signupTab.classList.remove("active");
        
        // Mainkan Display Block/None
        if (loginForm) loginForm.style.display = "block";
        if (signupForm) signupForm.style.display = "none";
    }

    /* Fungsi beralih ke mode SignUp (UI LOGIC) */
    function showSignupForm() {
        if (loginTab) loginTab.classList.remove("active");
        if (signupTab) signupTab.classList.add("active");
        
        // Mainkan Display Block/None
        if (loginForm) loginForm.style.display = "none";
        if (signupForm) signupForm.style.display = "block";
    }

    // === EVENT LISTENERS (YANG DISISAIN CUMA UI) ===
    
    setupPasswordToggle(loginPasswordInput, loginToggleIcon);
    setupPasswordToggle(signupPasswordInput, signupToggleIcon);

    if (loginTab) loginTab.addEventListener("click", showLoginForm);
    if (signupTab) signupTab.addEventListener("click", showSignupForm);
    if (switchToLoginLink) switchToLoginLink.addEventListener("click", showLoginForm);

    // === BAGIAN YANG GW HAPUS (PENTING!) ===
    // 1. Gw hapus logic 'loginForm.addEventListener("submit"...)'
    // 2. Gw hapus logic 'signupForm.addEventListener("submit"...)'
    // ALASANNYA: Biar saat lu klik tombol, browser langsung ngirim data ke Laravel (Route)
    // tanpa dicegat sama Javascript alert-alert boongan itu.

});