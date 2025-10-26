document.addEventListener("DOMContentLoaded", function() {

    // === ELEMEN-ELEMEN ===
    const loginTab = document.getElementById("login-tab");
    const signupTab = document.getElementById("signup-tab");
    // Formulir
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    // Link "Already have an account? Login"
    const switchToLoginLink = document.getElementById("switch-to-login");
    // Toggle Password (Login)
    const loginPasswordInput = document.getElementById("login-password");
    const loginToggleIcon = document.getElementById("login-toggle-icon");
    // Toggle Password (SignUp)
    const signupPasswordInput = document.getElementById("signup-password");
    const signupToggleIcon = document.getElementById("signup-toggle-icon");
    // Path Ikon Mata
    const eyeOpenIcon = "src/assets/eye-open.svg";
    const eyeClosedIcon = "src/assets/eye-closed.svg";


    /**
     * Fungsi  untuk toggle password
     * @param {HTMLInputElement} input 
     * @param {HTMLImageElement} icon 
     */
    function setupPasswordToggle(input, icon) {
        // Klik pada ikon (pembungkusnya)
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

    /**
     * Fungsi untuk beralih ke mode Login
     */
    function showLoginForm() {
        loginTab.classList.add("active");
        signupTab.classList.remove("active");
        
        loginForm.style.display = "block";
        signupForm.style.display = "none";
    }

    /**
     * Fungsi untuk beralih ke mode SignUp
     */
    function showSignupForm() {
        loginTab.classList.remove("active");
        signupTab.classList.add("active");
        
        loginForm.style.display = "none";
        signupForm.style.display = "block";
    }

    // === EVENT LISTENERS ===

 
    setupPasswordToggle(loginPasswordInput, loginToggleIcon);
    setupPasswordToggle(signupPasswordInput, signupToggleIcon);


    loginTab.addEventListener("click", showLoginForm);


    signupTab.addEventListener("click", showSignupForm);


    switchToLoginLink.addEventListener("click", showLoginForm);

    //  Simulasi Login 
    loginForm.addEventListener("submit", function(event) {
        event.preventDefault(); 
        const email = document.getElementById("login-email").value;
        const password = loginPasswordInput.value;

        if (email === "admin@gmail.com" && password === "12345") {
            sessionStorage.setItem("isLoggedIn", "true");
            window.location.href = "dashboard.html";
        } else {
            alert("Email atau password salah!");
        }
    });

    // Simulasi SignUp 
    signupForm.addEventListener("submit", function(event) {
        event.preventDefault();
        
        alert("Akun berhasil dibuat! Silakan Login.");
        
       
        showLoginForm(); 
    });

});