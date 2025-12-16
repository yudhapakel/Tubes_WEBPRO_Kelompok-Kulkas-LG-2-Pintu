// File: src/js/dashboard.js (VERSI PERBAIKAN)#
document.addEventListener("DOMContentLoaded", async function() {
    await loadComponent("#navbar-placeholder", "src/assets/components/_navbar.html");
    await loadComponent("#footer-placeholder", "src/assets/components/_footer.html");
    setActiveLink("dashboard");
    const logoutButton = document.getElementById("logout-btn");
    if (logoutButton) {
        logoutButton.addEventListener("click", function() {
            logout(); 
        });
    }

    console.log("Dashboard, Navbar, dan Footer sukses dimuat!");
});