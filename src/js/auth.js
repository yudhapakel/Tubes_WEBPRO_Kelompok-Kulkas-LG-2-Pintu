
/**
 * Memeriksa apakah pengguna sudah login.
 * Jika belum, akan "menendang" pengguna ke index.html
 */
function checkLoginStatus() {
    const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
    
    const currentPage = window.location.pathname.split('/').pop();

    if (!isLoggedIn && currentPage !== "index.html") {
        window.location.href = "index.html";
    } else {
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.visibility = "visible";
        });
    }
}

/**
 * Menjalankan proses logout
 */
function logout() {
    sessionStorage.removeItem("isLoggedIn");
    window.location.href = "index.html";
}

// Jalankan pengecekan ini secara otomatis
checkLoginStatus();