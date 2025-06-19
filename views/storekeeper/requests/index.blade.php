<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки из магазинов</title>
</head>

<body>
    <h1>Заявки из магазинов</h1>

    @if ($requests->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Товар</th>
                <th>Количество</th>
                <th>Причина</th>
                <th>Создатель</th>
                <th>Дата создания</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
            <tr>
                <td>{{ $request->product->name }}</td>
                <td>{{ $request->requested_quantity }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Нет заявок.</p>
    @endif

    <a href="{{ route('storekeeper.dashboard') }}">Назад на главную</a>
</body>

</html>
