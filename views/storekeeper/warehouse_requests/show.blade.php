<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали заявки на склад</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Детали заявки на склад</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Товар: {{ $warehouseMissingProductRequest->product->name }}</h5>
                <p class="card-text">Количество: {{ $warehouseMissingProductRequest->requested_quantity }}</p>
                <p class="card-text">Причина: {{ $warehouseMissingProductRequest->reason }}</p>
                <p class="card-text">Статус: {{ $warehouseMissingProductRequest->status }}</p>
            </div>
        </div>

        <form method="POST"
            action="{{ route('storekeeper.warehouse_requests.updateStatus', $warehouseMissingProductRequest->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="status">Изменить статус:</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ $warehouseMissingProductRequest->status == 'pending' ? 'selected' : ''
                        }}>В ожидании</option>
                    <option value="approved" {{ $warehouseMissingProductRequest->status == 'approved' ? 'selected' :
                        '' }}>Одобрено</option>
                    <option value="rejected" {{ $warehouseMissingProductRequest->status == 'rejected' ? 'selected' :
                        '' }}>Отклонено</option>
                    <option value="processed" {{ $warehouseMissingProductRequest->status == 'processed' ? 'selected'
                        : '' }}>В обработке</option>
                    <option value="completed" {{ $warehouseMissingProductRequest->status == 'completed' ? 'selected'
                        : '' }}>Выполнено</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>

        <a href="{{ route('storekeeper.dashboard') }}" class="btn btn-secondary mt-3">Назад на главную</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
