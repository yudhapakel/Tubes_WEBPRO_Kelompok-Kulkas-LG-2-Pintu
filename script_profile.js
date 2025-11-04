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

saveBtn.addEventListener("click", () => {
  modal.style.display = "flex";
});

function closeModal() {
  modal.style.display = "none";
}
