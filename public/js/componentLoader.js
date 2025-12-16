// File: js/componentLoader.js

function loadComponent(placeholderId, componentPath) {
    const placeholder = document.getElementById(placeholderId);
    if (placeholder) {
        fetch(componentPath)
            .then(response => {
                if (!response.ok) {
                    // Cek di sini: apakah path ke file HTML sudah benar?
                    throw new Error(`Error loading ${componentPath}: ${response.status}. Cek path!`);
                }
                return response.text();
            })
            .then(html => {
                placeholder.innerHTML = html;
            })
            .catch(error => console.error(error.message));
    }
}

// Jalankan fungsi saat DOM sudah siap
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan path ini benar relatif dari dashboard.html!
    // Asumsi: Navbar dan Footer berada di folder 'src/assets/components'
    
    loadComponent('navbar-placeholder', 'src/assets/components/_navbar.html');
    loadComponent('footer-placeholder', 'src/assets/components/_footer.html');
});

// File: js/payment.js

document.addEventListener('DOMContentLoaded', async function() {
    // Memuat Navbar dan Footer
    await loadComponent('#navbar-placeholder', 'src/assets/components/_navbar.html');
    await loadComponent('#footer-placeholder', 'src/assets/components/_footer.html');

    // Mengatur tautan Navbar 'Payment' menjadi aktif
    setActiveLink("payment"); 
    
    // Logika lain khusus halaman pembayaran (misalnya tombol Bayar) bisa ditambahkan di sini.
});