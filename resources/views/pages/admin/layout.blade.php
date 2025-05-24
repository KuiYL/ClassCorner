<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка | @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <style>
        :root {
            --primary: #555eb1;
            --success: #dc3545;
            --danger: #dc3545;
            --bg-card: #ffffff;
            --bg-light: #f8f9fa;
            --text-dark: #212529;
            --text-muted: #212529;
            --border-radius: 10px;
        }

        .admin-dashboard {
            padding: 2rem;
            max-width: 1100px;
            margin: auto;
        }

        .stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .stat-card {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 200px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card h4 {
            margin: 0 0 0.5rem;
            color: #212529;
            font-size: 1rem;
            font-weight: 600;
        }

        .stat-card p {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            margin: 0;
            text-align: right;
        }

        .section {
            background-color: var(--bg-card);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .section h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            color: #212529;
        }

        ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        ul li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        ul li:last-child {
            border-bottom: none;
        }

        ul li small {
            color: #212529;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }

        /* Прогресс студентов */
        .progress-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
            font-weight: 500;
        }

        .progress-bar-container {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
        }

        .progress-bar {
            height: 100%;
            color: white;
            text-align: right;
            padding-right: 10px;
            font-size: 0.9rem;
            transition: width 0.3s ease-in-out;
        }

        /* Цвета прогресс-бара */
        .progress-bar.bg-success {
            background-color: var(--success);
        }

        .progress-bar.bg-warning {
            background-color: #ffc107;
        }

        .progress-bar.bg-danger {
            background-color: #dc3545;
        }

        /* Таблица заданий */
        .table {
            width: 100%;
        }

        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .table td {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: #212529;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: bold;
            min-width: 120px;
            color: #212529;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .data-table thead {
            background-color: #f1f3f5;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .data-table td::before {
            content: attr(data-label);
            font-weight: bold;
            display: none;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination .page-item {
            display: inline-block;
            margin: 0 0.25rem;
        }

        .pagination .page-link {
            display: block;
            padding: 0.5rem 0.75rem;
            color: #555eb1;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
        }

        .pagination .active .page-link {
            background-color: #555eb1;
            color: #fff;
            border-color: #555eb1;
        }

        .btn {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .btn-primary {
            background-color: #555eb1;
            color: #fff;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .data-table {
                display: block;
                overflow-x: auto;
            }

            .data-table thead {
                display: none;
            }

            .data-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #eee;
                padding: 1rem;
                border-radius: 8px;
            }

            .data-table td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
            }

            .data-table td::before {
                content: attr(data-label);
                display: inline-block;
                font-weight: bold;
                min-width: 120px;
                color: #6c757d;
            }
        }

        /* Мобильная адаптация */
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
            }

            .stat-card {
                flex: 1 1 100%;
            }

            .progress-label {
                flex-direction: column;
                align-items: flex-start;
            }

            .progress-bar {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .stat-card h4 {
                font-size: 0.9rem;
            }

            .stat-card p {
                font-size: 1.2rem;
            }

            .progress-label {
                font-size: 0.9rem;
            }

            .progress-bar {
                font-size: 0.8rem;
            }

            ul li {
                font-size: 0.9rem;
            }
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .data-table thead {
            background-color: #f1f3f5;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .data-table td::before {
            content: attr(data-label);
            font-weight: bold;
            display: none;
        }

        .pagination .page-item .page-link {
            color: #555eb1;
            border: 1px solid #ddd;
            margin: 0 0.25rem;
            border-radius: 4px;
        }

        .pagination .page-item.active .page-link {
            background-color: #555eb1;
            color: white;
            border-color: #555eb1;
        }

        .btn {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .btn-primary {
            background-color: #555eb1;
            color: #fff;
            border: none;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-complete {
            background-color: #28a745;
            color: #fff;
            border: none;
        }

        .btn-view {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-form {
            margin: 0;
        }

        /* Мобильная версия */
        @media (max-width: 768px) {
            .data-table thead {
                display: none;
            }

            .data-table tbody tr {
                display: block;
                border: 1px solid #eee;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .data-table td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                font-size: 0.9rem;
            }

            .actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .actions .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'admin'])
    <div class="topbar">
        @include('layout.topbar')

        <main>
            @yield('content')
        </main>

        @stack('scripts')
    </div>
</body>

</html>
