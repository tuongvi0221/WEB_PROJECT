document.getElementById('s_payment_method').addEventListener('change', function () {
    var qrDiv = document.getElementById('qr_payment');
    if (this.value === 'QR') {
        qrDiv.style.display = 'block';
    } else {
        qrDiv.style.display = 'none';
    }
});
