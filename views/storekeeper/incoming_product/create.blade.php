@extends('layouts/storekeeperlayout')

@section('content')
        <h1>Приемка товара</h1>

        <div class="form-group">
            <label for="barcode">Штрихкод:</label>
            <input type="text" id="barcode" class="form-control" autofocus>
        </div>
        <button id="scan-button" class="btn btn-primary">Сканировать</button>

        <div id="product-info" class="mt-3"></div>

        <h2>Товары для приемки:</h2>
        <table id="incoming-products-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <button id="submit-button" class="btn btn-success">Принять товар</button>

        <a href="{{ route('storekeeper.dashboard') }}" class="btn btn-secondary mt-3">Назад на главную</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#scan-button').click(function() {
                var barcode = $('#barcode').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('storekeeper.incoming_product.scan') }}",
                    type: "POST",
                    {
                        barcode: barcode
                    },
                    success: function(response) {
                        if (response.exists) {
                            $('#product-info').html('<h3>' + response.product.name + '</h3>' +
                                '<p>Цена: ' + response.product.price + '</p>' +
                                '<label for="quantity">Количество:</label>' +
                                '<input type="number" id="quantity" min="1">' +
                                '<button id="add-button" data-product-id="' + response.product.id + '">Добавить</button>');

                            $('#add-button').click(function() {
                                var productId = $(this).data('product-id');
                                var quantity = $('#quantity').val();
                                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                                $.ajax({
                                    url: "{{ route('storekeeper.incoming_product.add') }}",
                                    type: "POST",
                                    {
                                        _token: csrfToken,
                                        product_id: productId,
                                        quantity: quantity
                                    },
                                    success: function(response) {
                                        alert(response.message);
                                        loadIncomingProducts();
                                    },
                                    error: function(error) {
                                        alert('Ошибка добавления товара');
                                    }
                                });
                            });

                        } else {
                            $('#product-info').html('<p>' + response.message + '</p>' +
                                '<button>Добавить товар</button>');
                        }
                    },
                    error: function(error) {
                        alert('Ошибка сканирования штрихкода');
                    }
                });
            });

            function loadIncomingProducts() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('storekeeper.incoming_product.load') }}",
                    type: "GET",
                    success: function(response) {
                        $('#incoming-products-table tbody').html(response);

                        // Add event listener for remove buttons
                        $('.remove-button').click(function() {
                            var productId = $(this).data('product-id');
                            removeProduct(productId);
                        });
                    },
                    error: function(error) {
                        alert('Ошибка загрузки товаров');
                    }
                });
            }

            function removeProduct(productId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('storekeeper.incoming_product.remove') }}",
                    type: "POST",
                    {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId
                    },
                    success: function(response) {
                        alert(response.message);
                        loadIncomingProducts();
                    },
                    error: function(error) {
                        alert('Ошибка удаления товара');
                    }
                });
            }

            $('#submit-button').click(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('storekeeper.incoming_product.store') }}",
                    type: "POST",
                    {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ route('storekeeper.dashboard') }}";
                    },
                    error: function(error) {
                        alert('Ошибка при сохранении');
                    }
                });
            });

            // Load initial products on page load
            loadIncomingProducts();
        });
    </script>
@endsection
