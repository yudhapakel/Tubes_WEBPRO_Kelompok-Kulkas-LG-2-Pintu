// Preview Foto Profil
document.getElementById('uploadPic').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(event) {
      document.getElementById('profilePic').src = event.target.result;
    };
    reader.readAsDataURL(file);
  }
});

// Simpan perubahan profil (simulasi)
function simpanProfil() {
  const nama = document.getElementById('nama').value;
  alert('Profil atas nama ' + nama + ' berhasil disimpan!');
}
