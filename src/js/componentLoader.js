// File: js/utils/componentLoader.js

/**
 * Memuat komponen HTML dari file eksternal ke dalam elemen placeholder.
 * @param {string} selector 
 * @param {string} url 
 * @returns {Promise<void>}
 */
async function loadComponent(selector, url) {
    try {
     
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Gagal memuat ${url}: ${response.statusText}`);
        }
        
        const html = await response.text();
        
       
        const placeholder = document.querySelector(selector);
        if (placeholder) {
            placeholder.innerHTML = html;
        } else {
            console.warn(`Elemen ${selector} tidak ditemukan.`);
        }
        
    } catch (error) {
        console.error("Error memuat komponen:", error);
    }
}

/**
 * Menandai link navbar yang aktif berdasarkan data-link.
 * @param {string} pageIdentifier 
 */
function setActiveLink(pageIdentifier) {
    // Cari semua link di dalam navbar yang punya "data-link"
    const navLinks = document.querySelectorAll('.main-navbar .navbar-link[data-link]');
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        
        if (link.getAttribute('data-link') === pageIdentifier) {
            link.classList.add('active');
        }
    });
}