

document.addEventListener("DOMContentLoaded", async function() {
    

    await loadComponent("#navbar-placeholder", "src/assets/components/_navbar.html");
    await loadComponent("#footer-placeholder", "src/assets/components/_footer.html");
    setActiveLink("document-verification");
    
    const logoutButton = document.getElementById("logout-btn");
    if (logoutButton) {
        logoutButton.addEventListener("click", function() {
            logout(); 
        });
    }

  

    // Variabel untuk "mengingat" item mana sedang diklik
    let currentlySelectedDocumentItem = null;

    //  Ambil elemen-elemen dari KEDUA modal
    const detailModalOverlay = document.getElementById('detail-modal');
    const detailModalCloseBtn = document.getElementById('modal-close-btn');
    const verifyBtn = document.getElementById('verifikasi-btn');
    const modalStatusText = document.getElementById('modal-preview-status');
    const modalViewBtn = document.getElementById('modal-view-btn');
    const modalDownloadBtn = document.getElementById('modal-download-btn');

    const successModalOverlay = document.getElementById('success-modal');
    const successKembaliBtn = document.getElementById('success-kembali-btn');

    // Fungsi untuk membuka modal DETAIL
    function openDetailModal(status, docUrl) {
        if (!detailModalOverlay || !verifyBtn || !modalStatusText) return; 

        // Atur Teks Status & Tombol Verifikasi
        if (status === 'verified') {
            modalStatusText.textContent = 'Sudah Terverifikasi';
            modalStatusText.className = 'status status-verified';
            verifyBtn.style.display = 'none'; 
        } else if (status === 'declined') {
            modalStatusText.textContent = 'Ditolak';
            modalStatusText.className = 'status status-declined';
            verifyBtn.style.display = 'none'; 
        } else if (status === 'not-verified') {
            modalStatusText.textContent = 'Belum di Verifikasi';
            modalStatusText.className = 'status status-not-verified';
            verifyBtn.style.display = 'block'; 
        }
        
        // Atur link tombol "Lihat" dan "Download"
        const safeDocUrl = docUrl || 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
        if(modalViewBtn) modalViewBtn.href = safeDocUrl;
        if(modalDownloadBtn) modalDownloadBtn.href = safeDocUrl;
        detailModalOverlay.classList.remove('hidden');
    }

    // 3. Fungsi  menutup modal DETAIL
    function closeDetailModal() {
        if (detailModalOverlay) {
            detailModalOverlay.classList.add('hidden');
        }
    }

    // Fungsi umembuka modal SUKSES
    function openSuccessModal() {
        if (successModalOverlay) {
            successModalOverlay.classList.remove('hidden');
        }
    }

    // Fungsi untuk menutup modal SUKSES
    function closeSuccessModal() {
        if (successModalOverlay) {
            successModalOverlay.classList.add('hidden');
        }
    }

    // Listener klik di LIST
    const listContainer = document.querySelector('.document-list');
    if (listContainer) {
        listContainer.addEventListener('click', function(event) {
            const clickedItem = event.target.closest('.list-item');
            
            if (clickedItem) {
                
                currentlySelectedDocumentItem = clickedItem; 
                
                const status = clickedItem.dataset.status;
                const docUrl = clickedItem.dataset.docUrl;
                
                openDetailModal(status, docUrl);
            }
        });
    }


    if (detailModalCloseBtn) detailModalCloseBtn.addEventListener('click', closeDetailModal);
    if (detailModalOverlay) {
        detailModalOverlay.addEventListener('click', function(event) {
            if (event.target === detailModalOverlay) {
                closeDetailModal();
            }
        });
    }

  
    if (successKembaliBtn) successKembaliBtn.addEventListener('click', closeSuccessModal);
    if (successModalOverlay) {
        successModalOverlay.addEventListener('click', function(event) {
            if (event.target === successModalOverlay) {
                closeSuccessModal();
            }
        });
    }

  
    //  untuk tombol "Verifikasi dokumen"
    if (verifyBtn) {
        verifyBtn.addEventListener('click', function() {
            // Cek apakah kita "mengingat" item yang diklik
            if (currentlySelectedDocumentItem) {
                
              
                const statusSpan = currentlySelectedDocumentItem.querySelector('.status');
                if (statusSpan) {
                    statusSpan.textContent = 'Verified';
                    statusSpan.className = 'status status-verified';
                }
                
                currentlySelectedDocumentItem.dataset.status = 'verified';
                currentlySelectedDocumentItem = null;
            }

          
            closeDetailModal();
            openSuccessModal();
        });
    }
   

    console.log("Halaman Verifikasi Dokumen, Modal Sukses, dan Update Status sukses dimuat!");
});