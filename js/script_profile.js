// --- GANTI FOTO PROFIL ---
const profileInput = document.getElementById("profileInput");
const profilePreview = document.getElementById("profilePreview");
const changePhotoBtn = document.getElementById("changePhotoBtn");

changePhotoBtn.addEventListener("click", () => {
  profileInput.click();
});

profileInput.addEventListener("change", () => {
  const file = profileInput.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      profilePreview.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});


const saveBtn = document.getElementById("saveBtn");
const modal = document.getElementById("successModal");
const closeModalBtn = document.getElementById("closeModalBtn"); 

saveBtn.addEventListener("click", (e) => {
  e.preventDefault();
  modal.style.display = "flex"; 
});

function closeModal() {
  modal.style.display = "none";
  window.location.href = "dashboard.html"; 
}

if (closeModalBtn) {
  closeModalBtn.addEventListener("click", closeModal);
}

const logoutBtn = document.getElementById("logoutBtn");
if (logoutBtn) {
  logoutBtn.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Anda telah logout!");
    window.location.href = "login.html";
  });
}