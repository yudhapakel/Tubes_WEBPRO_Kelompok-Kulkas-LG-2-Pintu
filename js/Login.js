

const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";

if (isLoggedIn) {
    window.location.href = "dashboard.html";
} else {
    document.addEventListener('DOMContentLoaded', () => {
        document.body.style.visibility = "visible";
    });
}



document.addEventListener("DOMContentLoaded", function() {

    // === ELEMEN-ELEMEN ===
    const loginTab = document.getElementById("login-tab");
    const signupTab = document.getElementById("signup-tab");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const switchToLoginLink = document.getElementById("switch-to-login");
    const loginPasswordInput = document.getElementById("login-password");
    const loginToggleIcon = document.getElementById("login-toggle-icon");
    const signupPasswordInput = document.getElementById("signup-password");
    const signupToggleIcon = document.getElementById("signup-toggle-icon");
    
    // Path Ikon Mata
    const eyeOpenIcon = "src/assets/eye-open.svg";
    const eyeClosedIcon = "src/assets/eye-closed.svg";


    /** Fungsi untuk toggle password*/
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

    /** Fungsi beralih ke mode Login */
    function showLoginForm() {
        if (loginTab) loginTab.classList.add("active");
        if (signupTab) signupTab.classList.remove("active");
        if (loginForm) loginForm.style.display = "block";
        if (signupForm) signupForm.style.display = "none";
    }

    /* Fungsi beralih ke mode SignUp*/
    function showSignupForm() {
        if (loginTab) loginTab.classList.remove("active");
        if (signupTab) signupTab.classList.add("active");
        if (loginForm) loginForm.style.display = "none";
        if (signupForm) signupForm.style.display = "block";
    }

    // === EVENT LISTENERS ===
    
    setupPasswordToggle(loginPasswordInput, loginToggleIcon);
    setupPasswordToggle(signupPasswordInput, signupToggleIcon);

    if (loginTab) loginTab.addEventListener("click", showLoginForm);
    if (signupTab) signupTab.addEventListener("click", showSignupForm);
    if (switchToLoginLink) switchToLoginLink.addEventListener("click", showLoginForm);


    if (loginForm) {
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
    }

    // Simulasi SignUp 
    if (signupForm) {
        signupForm.addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Akun berhasil dibuat! Silakan Login.");
            showLoginForm(); 
        });
    }

});
