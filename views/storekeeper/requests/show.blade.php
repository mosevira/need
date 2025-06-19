<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали заявки</title>
</head>

<body>
    <h1>Детали заявки</h1>

    <p>Товар: {{ $missingProductRequest->product->name }}</p>
    <p>Количество: {{ $missingProductRequest->requested_quantity }}</p>
    <p>Причина: {{ $missingProductRequest->reason }}</p>
    <p>Статус: {{ $missingProductRequest->status }}</p>

    <form method="POST" action="{{ route('storekeeper.requests.updateStatus', $missingProductRequest->id) }}">
        @csrf
        @method('PUT')
        <label for="status">Изменить статус:</label>
        <select name="status" id="status">
            <option value="pending" {{ $missingProductRequest->status == 'pending' ? 'selected' : '' }}>В ожидании</option>
            <option value="approved" {{ $missingProductRequest->status == 'approved' ? 'selected' : '' }}>Одобрено</option>
            <option value="rejected" {{ $missingProductRequest->status == 'rejected' ? 'selected' : '' }}>Отклонено</option>
            <option value="processed" {{ $missingProductRequest->status == 'processed' ? 'selected' : '' }}>В обработке</option>
            <option value="completed" {{ $missingProductRequest->status == 'completed' ? 'selected' : '' }}>Выполнено</option>
        </select>
        <button type="submit">Сохранить</button>
    </form>

    <a href="{{ route('storekeeper.dashboard') }}">Назад на главную</a>
</body>

</html>
