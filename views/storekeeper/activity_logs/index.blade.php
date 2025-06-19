<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История действий</title>
</head>
<body>
    <h1>История действий</h1>

    @if ($activityLogs->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Действие</th>
                    <th>Описание</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activityLogs as $log)
                    <tr>
                        <td>{{ $log->activity }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $activityLogs->links() }}
    @else
        <p>Нет записей об активности.</p>
    @endif

    <a href="{{ route('storekeeper.dashboard') }}">Назад на главную</a>
</body>
</html>
