<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список Продаж</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <!-- Подключение CSS Bootstrap через CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <h1><a class="" href="#">Аккаунт продавца</a></h1>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">
                            <i class="fas fa-sign-out-alt"></i> Выход
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <i class="fas fa-home"></i> Главная
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="#">
                                <i class="fas fa-plus-circle"></i>Накладные со склада
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-plus-circle"></i> Товар в магазине
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-store"></i> Списать товар
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('sales.index')}}">
                                <i class="fas fa-store"></i> Продажа
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('storekeeper.shipments.index') }}">
                                <i class="fas fa-truck"></i> Shipments
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-box"></i> Приемка товаров
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-history"></i> Действия
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1>Список Продаж</h1>

                <a href="{{ route('sales.create') }}">Создать продажу</a>

                @if(session('success'))
                    <p>{{ session('success') }}</p>
                @endif

                @if($sales->isEmpty())
                    <p>Нет продаж для отображения.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Сумма</th>
                                <th>Описание</th>
                                <th>Дата Создания</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->amount }} ₽</td>
                                    <td>{{ $sale->description }}</td>
                                    <td>{{ $sale->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                </main>
</body>
</html>
