<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-primary: #007bff;
            --bs-sidebar-width: 280px;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--bs-primary);
        }

        #sidebar {
            width: var(--bs-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: var(--bs-sidebar-width);
            padding: 20px;
            width: calc(100% - var(--bs-sidebar-width));
        }

        .sidebar-link {
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-link.active {
            background-color: var(--bs-primary);
            color: white !important;
        }

        .stat-card {
            transition: transform 0.2s;
            border-left: 4px solid var(--bs-primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -var(--bs-sidebar-width);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Навбар -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">Панель администратора</a>

            <div class="d-flex align-items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i> Выход
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <div class="container-fluid">
        <div class="row">
            <!-- Сайдбар -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link sidebar-link active" href="{{route('dashboard.admin')}}">
                                <i class="bi bi-house-door me-2"></i> Главная
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i> Пользователи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('branches.index') }}">
                                <i class="bi bi-shop me-2"></i> Филиалы
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('storekeeper.branches.index') }}">
                                <i class="bi bi-graph-up me-2"></i> Аналитика
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Основное содержимое -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Общая статистика</h1>
                </div>

                <!-- Карточки статистики -->
                <div class="row mb-4">
                    @foreach($roleCounts as $roleCount)
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">{{ ucfirst($roleCount->role) }}</h6>
                                        <h3>{{ $roleCount->count }}</h3>
                                    </div>
                                    <div class="icon-shape bg-primary text-white rounded-circle p-3">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Таблица продаж -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Продажи</h5>
                    </div>
                    <div class="card-body">
                        @if($salesData->isEmpty())
                            <div class="alert alert-info">Нет данных о продажах</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Продавец</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salesData as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->sales_sum_amount ?? 0 }} ₽</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
