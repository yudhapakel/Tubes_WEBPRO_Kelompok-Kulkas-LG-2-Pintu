// ===== Helpers =====
const $ = (sel, el = document) => el.querySelector(sel);

const form = $('#form');
const fileInput = $('#file');
const drop = $('#drop');
const bar = $('#bar');
const progressWrap = drop.querySelector('.progress');
const fileInfo = $('#fileInfo');
const agree = $('#agree');
const submitBtn = $('#submitBtn');
const cancelBtn = $('#cancelBtn');
const modal = $('#modal');
const toDashboard = $('#toDashboard');

// Enable submit if terms checked & file chosen & form valid
function updateSubmitState() {
  const hasFile = !!fileInput.files.length;
  const formOK = form.checkValidity();
  submitBtn.disabled = !(agree.checked && hasFile && formOK);
}

agree.addEventListener('change', updateSubmitState);
form.addEventListener('input', updateSubmitState);

// Drag & drop states
['dragenter', 'dragover'].forEach(evt => {
  drop.addEventListener(evt, e => { e.preventDefault(); drop.style.background = '#eef2ff' });
});
['dragleave', 'drop'].forEach(evt => {
  drop.addEventListener(evt, e => { e.preventDefault(); drop.style.background = 'var(--panel)' });
});
drop.addEventListener('drop', (e) => {
  if (e.dataTransfer.files.length) {
    fileInput.files = e.dataTransfer.files;
    onFileSelected();
  }
});
fileInput.addEventListener('change', onFileSelected);

function bytesToSize(n) {
  if (n < 1024) return n + ' B';
  if (n < 1024 * 1024) return (n / 1024).toFixed(1) + ' KB';
  return (n / 1024 / 1024).toFixed(2) + ' MB';
}

function onFileSelected() {
  const f = fileInput.files[0];
  if (!f) return;
  if (!/(pdf|jpg|jpeg|png)$/i.test(f.name.split('.').pop())) {
    alert('Format harus .pdf/.jpg/.png');
    fileInput.value = '';
    return updateSubmitState();
  }
  if (f.size > 5 * 1024 * 1024) {
    alert('Ukuran file maksimal 5 MB');
    fileInput.value = '';
    return updateSubmitState();
  }
  fileInfo.textContent = 'Dipilih: ' + f.name + ' (' + bytesToSize(f.size) + ')';
  progressWrap.hidden = false;
  bar.style.width = '0%';
  updateSubmitState();
}

// Fake upload + success modal
submitBtn.addEventListener('click', (e) => {
  e.preventDefault();
  if (submitBtn.disabled) return;
  if (!form.reportValidity()) return;

  let p = 0;
  const timer = setInterval(() => {
    p += Math.random() * 18;
    if (p >= 100) { p = 100; clearInterval(timer); showSuccess(); }
    bar.style.width = p + '%';
  }, 200);
});

function showSuccess() {
  modal.setAttribute('open', '');
  modal.removeAttribute('aria-hidden');
}

modal.addEventListener('click', (e) => {
  if (e.target === modal) { modal.removeAttribute('open'); modal.setAttribute('aria-hidden', 'true'); }
});

toDashboard?.addEventListener('click', () => {
  modal.removeAttribute('open');
  modal.setAttribute('aria-hidden', 'true');
  alert('Mock: kembali ke dashboard');
});

cancelBtn.addEventListener('click', () => {
  form.reset();
  fileInput.value = '';
  progressWrap.hidden = true;
  fileInfo.textContent = '';
  updateSubmitState();
});
