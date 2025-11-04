document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("fileInput");
  const profileImage = document.getElementById("profileImage");
  const saveBtn = document.getElementById("saveBtn");
  const logoutBtn = document.getElementById("logoutBtn");

  // Ganti foto profil
  fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        profileImage.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  // Saat tombol Save diklik
  saveBtn.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Profil berhasil disimpan!");
    window.location.href = "dashboard.html"; // pindah ke dashboard
  });

  // Saat tombol Logout diklik
  logoutBtn.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Anda telah logout!");
    window.location.href = "login.html";
  });
});
