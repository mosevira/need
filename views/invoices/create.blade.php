@extends('layouts/storekeeperlayout')

@section('content')

<div class="container">
    <h1 class="mb-4">Создать накладную</h1>

    <!-- Блок сканирования -->
   <div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="fas fa-barcode"></i> Сканер товаров</span>
            <button id="toggle-scanner" class="btn btn-sm btn-light">
                <i class="fas fa-camera"></i> <span id="scanner-state">Включить сканер</span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="scanner-section" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <video id="scanner-preview" class="w-100" style="background: #000;"></video>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="manual-barcode">Или введите вручную:</label>
                        <input type="text"
                               id="manual-barcode"
                               class="form-control"
                               placeholder="Штрих-код товара">
                    </div>
                    <div id="scan-result" class="alert mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div id="scanner-placeholder" class="text-center py-3">
            <i class="fas fa-camera fa-3x text-muted mb-2"></i>
            <p class="text-muted">Нажмите "Включить сканер" для активации камеры</p>
        </div>
    </div>
</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
        @csrf

        @if(Auth::user()->role === 'admin')
            <div class="form-group mb-3">
                <label for="branch_id">Филиал:</label>
                <select class="form-control" name="branch_id" id="branch_id" required>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="form-group mb-3">
                <label for="branch_id">Филиал:</label>
                <input type="text" class="form-control" value="{{ Auth::user()->branch->name }}" readonly>
                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
            </div>
        @endif

        <div class="form-group mb-3">
            <label for="date">Дата:</label>
            <input type="date" class="form-control" name="date" id="date" required value="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="mb-0">Товары в накладной:</label>
                <small class="text-muted">Отсканировано: <span id="scanned-count">0</span></small>
            </div>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th width="150">Количество</th>
                        <th width="80">Действия</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    @foreach($products as $product)
                    <tr data-product-id="{{ $product->id }}" class="product-row">
                        <td>
                            {{ $product->name }}
                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        </td>
                        <td>{{ number_format($product->price, 2) }} ₽</td>
                        <td>
                            <input type="number" class="form-control product-quantity"
                                   name="product_quantities[{{ $product->id }}]"
                                   value="0" min="0" data-product-id="{{ $product->id }}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-product"
                                    data-product-id="{{ $product->id }}" title="Удалить">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Сохранить накладную
            </button>
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Отмена
            </a>
        </div>
    </form>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleScannerBtn = document.getElementById('toggle-scanner');
    const scannerSection = document.getElementById('scanner-section');
    const scannerPlaceholder = document.getElementById('scanner-placeholder');
    const scannerPreview = document.getElementById('scanner-preview');
    const manualBarcodeInput = document.getElementById('manual-barcode');
    const scanResult = document.getElementById('scan-result');
    const scannedCount = document.getElementById('scanned-count');

    let scannerActive = false;
    let quaggaInstance = null;

    // Включение/выключение сканера
    toggleScannerBtn.addEventListener('click', toggleScanner);

    // Обработка ручного ввода
    manualBarcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            handleBarcode(this.value.trim());
            this.value = '';
        }
    });

    function toggleScanner() {
        if (!scannerActive) {
            initScanner();
            scannerSection.style.display = 'block';
            scannerPlaceholder.style.display = 'none';
            toggleScannerBtn.innerHTML = '<i class="fas fa-stop"></i> Выключить сканер';
            scannerActive = true;
        } else {
            stopScanner();
            scannerSection.style.display = 'none';
            scannerPlaceholder.style.display = 'block';
            toggleScannerBtn.innerHTML = '<i class="fas fa-camera"></i> Включить сканер';
            scannerActive = false;
        }
    }

    function initScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: scannerPreview,
                constraints: {
                    facingMode: "environment",
                    width: 640,
                    height: 480
                },
            },
            decoder: {
                readers: ['ean_reader', 'ean_8_reader', 'code_128_reader']
            }
        }, function(err) {
            if (err) {
                console.error(err);
                showResult('Ошибка инициализации сканера', 'danger');
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            handleBarcode(code);
        });
    }

    function stopScanner() {
        if (quaggaInstance) {
            Quagga.stop();
        }
    }

    function handleBarcode(barcode) {
        if (!barcode) return;

        showResult('Поиск товара...', 'info');

        fetch(`/api/products/by-barcode/${barcode}`)
            .then(response => {
                if (!response.ok) throw new Error('Товар не найден');
                return response.json();
            })
            .then(product => {
                addProductToInvoice(product);
                showResult(`Добавлен: ${product.name}`, 'success');
            })
            .catch(error => {
                console.error('Ошибка:', error);
                showResult(`Товар не найден! <a href="/products/create?barcode=${barcode}">Создать новый?</a>`, 'danger');
            });
    }

    function addProductToInvoice(product) {
        const quantityInput = document.querySelector(`input[name="product_quantities[${product.id}]"]`);

        if (quantityInput) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            quantityInput.dispatchEvent(new Event('change'));

            // Визуальное подтверждение
            const row = quantityInput.closest('tr');
            row.classList.add('bg-success-light');
            setTimeout(() => row.classList.remove('bg-success-light'), 1000);

            updateScannedCount();
        } else {
            showResult('Этот товар не доступен для вашего филиала', 'warning');
        }
    }

    function showResult(message, type) {
        scanResult.style.display = 'block';
        scanResult.className = `alert alert-${type}`;
        scanResult.innerHTML = message;
        setTimeout(() => scanResult.style.display = 'none', 3000);
    }

    function updateScannedCount() {
        const count = Array.from(document.querySelectorAll('.product-quantity'))
            .filter(input => parseInt(input.value) > 0).length;
        scannedCount.textContent = count;
    }

    // Обновляем счетчик при изменении количества вручную
    document.querySelectorAll('.product-quantity').forEach(input => {
        input.addEventListener('change', updateScannedCount);
    });
});
</script>
@endpush

@push('styles')
<style>
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.2) !important;
        transition: background-color 0.5s ease;
    }
    #scanner-preview {
        border-radius: 4px;
        max-height: 300px;
    }
</style>
@endpush
@endsection
