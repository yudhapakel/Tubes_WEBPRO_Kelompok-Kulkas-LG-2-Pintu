function payNow() {
    let rekening = document.getElementById("rekening").value;
    let bank = document.getElementById("bank").value;

    if (rekening === "" || bank === "") {
        alert("Harap isi data rekening dan bank terlebih dahulu!");
    } else {
        document.getElementById("popupSuccess").style.display = "flex";
    }
}

function goToInvoice() {
    window.location.href = "invoice.html";
}

function payNow() {
    window.location.href = "pembayaran_berhasil.html";
}