<!DOCTYPE html>
<html>
<head>
    <title>Предметы</title>
    <link rel="stylesheet" href="/assets/css/bootstrap5/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Школа</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/teachers">Преподаватели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/subjects">Предметы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout ({{ user.login }})</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">

        <h1>Предметы</h1>
        <div class="row">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col mb-3">
                <a class="btn btn-outline-dark" href="/subjects/create" role="button">Создать</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Картинка</th>
                        <th scope="col">Название предмета</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Длительность курса в часах</th>
                        <th scope="col">Действие</th>

                    </tr>
                    </thead>
                    <tbody>
                    {% for i, subject in subjects %}
                    <tr>
                        <th scope="row">{{ subject.id }}</th>
                        <td><img width="80" src="data:image/jpeg;base64,{{ subject.avatar }}"/></td>
                        <td>{{ subject.title }}</td>
                        <td>{{ subject.description }}</td>
                        <td>{{ subject.duration }}</td>
                        <td>
                            <a class="btn btn-xs btn-outline-dark" href="/subjects/edit/{{ subject.id }}" role="button">Изменить</a>
                            <a class="btn btn-xs btn-danger" href="/subjects/remove/{{ subject.id }}" role="button">Удалить</a>
                        </td>
                    </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="/assets/js/bootstrap5/bootstrap.min.js"></script>
</body>
</html>