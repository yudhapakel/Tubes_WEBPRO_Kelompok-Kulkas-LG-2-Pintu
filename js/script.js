// File: js/script.js

// Fungsi untuk memuat konten HTML ke placeholder (dari sebelumnya)
function loadComponent(placeholderId, componentPath) {
    const placeholder = document.getElementById(placeholderId);
    if (placeholder) {
        fetch(componentPath)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: Komponen ${componentPath} tidak ditemukan (${response.status}). Cek path folder Anda!`);
                }
                return response.text();
            })
            .then(html => {
                placeholder.innerHTML = html;
                
                // Tambahkan kelas 'active' ke link "Notification" setelah dimuat
                const notificationLink = placeholder.querySelector('.navbar-menu a[href="#"]');
                if (notificationLink) {
                     notificationLink.classList.add('active');
                }
            })
            .catch(error => console.error(error.message));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // === 1. Panggil Komponen Navbar dan Footer (PATH SUDAH DIPERBAIKI) ===
    loadComponent('navbar-placeholder', 'src/assets/components/_navbar.html');
    loadComponent('footer-placeholder', 'src/assets/components/_footer.html');


    // === 2. LOGIKA TAB SWITCHING BARU ===
    const tabs = document.querySelectorAll('.tabs a');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(event) {
            event.preventDefault(); 
            
            const targetId = tab.dataset.tabTarget;
            const targetContent = document.querySelector(targetId);

            // Hapus kelas aktif dari semua tab navigasi dan konten
            tabs.forEach(t => t.classList.remove('tab-active'));
            tabContents.forEach(c => c.classList.remove('active-content'));

            // Tambahkan kelas aktif ke tab dan konten yang diklik
            tab.classList.add('tab-active');
            targetContent.classList.add('active-content');
            
            // Re-bind event listener untuk modal pada konten yang baru aktif
            bindModalListeners(targetContent);
        });
    });


    // === 3. Logika Modal (Diperbarui agar bisa di-panggil ulang) ===
    const modal = document.getElementById('detailModal');
    const detailContent = document.getElementById('detailFormContent');
    const previewContent = document.getElementById('documentPreviewContent');
    const closeButtons = document.querySelectorAll('.close-button-bottom');

    function openModal() {
        detailContent.style.display = 'block';
        previewContent.style.display = 'none';
        modal.style.display = 'flex';
    }

    function closeModal() {
        detailContent.style.display = 'block';
        previewContent.style.display = 'none';
        modal.style.display = 'none';
    }
    
    // Tombol Lihat
    const viewDocumentBtn = document.getElementById('viewDocumentBtn');
    if (viewDocumentBtn) {
        viewDocumentBtn.addEventListener('click', function(event) {
            if (event) event.preventDefault(); 
            detailContent.style.display = 'none';
            previewContent.style.display = 'block';
        });
    }

    // Fungsi untuk mengikat event listener ke baris tabel
    function bindModalListeners(container) {
        // Hapus listener dari SEMUA baris di SEMUA tabel (pencegahan double click)
        const allRows = document.querySelectorAll('.notification-table tbody tr.clickable-row');
        allRows.forEach(row => {
            row.removeEventListener('click', openModal); 
        });

        // Ikat listener hanya pada baris di konten yang aktif (container)
        const activeRows = container.querySelectorAll('.notification-table tbody tr.clickable-row');
        activeRows.forEach(row => {
            row.addEventListener('click', openModal);
        });
    }
    
    // Bind listener saat pertama kali halaman dimuat (untuk konten default/Dokumen)
    const initialDocumentSection = document.getElementById('document-section');
    if (initialDocumentSection) {
        bindModalListeners(initialDocumentSection);
    }
    
    // Tombol "Kembali" berfungsi ganda
    closeButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); 

            if (previewContent.style.display === 'block') {
                detailContent.style.display = 'block';
                previewContent.style.display = 'none';
            } else {
                closeModal();
            }
        });
    });

    // Tutup modal ketika user mengklik di luar modal
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('click', function(event) {
    const logoutButton = event.target.closest('#logout-btn');
    if (logoutButton) {
        event.preventDefault(); 
        window.location.href = "index.html"; // <-- Redirection ke index.html
    }
});
    
});