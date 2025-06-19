document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode-input');
    
    barcodeInput.focus();
    
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            axios.post('/products/process-scan', {
                barcode: this.value
            }).then(response => {
                document.getElementById('scan-results').innerHTML = response.data.html;
                this.value = '';
            }).catch(error => {
                alert(error.response.data.error);
                if (error.response.data.action === 'add') {
                    window.location.href = '/products/create?barcode=' + encodeURIComponent(this.value);
                }
            });
        }
    });
});