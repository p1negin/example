<!DOCTYPE html>
<html>
<head>
    <title>Преподаватели</title>
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
    {% if error != null %}
    <div class="alert alert-danger">{{ error }}</div>
    {% endif %}

    {% if subject %}
    <h1>Изменить предмет</h1>
    {% else  %}
    <h1>Создать предмет</h1>
    {% endif %}

    <form method="post" action="/subjects/createAction" enctype="multipart/form-data">
        {% if subject %}
        <input type="hidden" name="subjectID" value="{{ subject.id }}"/>
        <center> <img width="300" src="data:image/jpeg;base64,{{ subject.avatar }}"/></center>
        {% endif %}
    <div class="mb-3 row">
        <label for="formFile" class="form-label">Загрузите картинку</label>
        <div class="col-sm-10">
            <input name="subjectAvatar" class="form-control" type="file" id="formFile"/>

        </div>
    </div>


    <div class = "mb-3 row">
        <label for="form-select-sm example" class="col-sm-2 col-form-label">Выберите преподавателя</label>
        <div class="col-sm-10">
            <select name="subjectTeachers[]" multiple="multiple" class="form-select form-select-sm" aria-label=".form-select-sm example">
                {% for i, teacher in teachers %}
                <option value="{{ teacher.id }}">{{ teacher.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="colFormLabel" class="col-sm-2 col-form-label">Название предмета</label>
        <div class="col-sm-10">
            <input type="text" value="{{ subject.title }}" name="subjectName" class="form-control" id="colFormLabel" placeholder="Название">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="colFormLabel" class="col-sm-2 col-form-label">Описание предмета</label>
        <div class="col-sm-10">
            <textarea class="form-control" id="colFormLabel"  name="subjectDescription" placeholder="Описание предмета">{{ subject.description }}</textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="colFormLabel" class="col-sm-2 col-form-label">Длительность в часах</label>
        <div class="col-sm-10">
            <input type="text" value="{{ subject.duration }}" class="form-control" id="colFormLabel" name="subjectDuration" placeholder="Длительность"/>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <td>
                <div class="d-grid gap-2">
                    {% if subject %}
                    <button type="submit" class="btn btn-outline-dark" name="changeSubjectBtn">Изменить предмет</button>
                    {% else %}
                    <button type="submit" class="btn btn-outline-dark" name="createSubjectBtn">Добавить предмет</button>
                    {% endif %}
                </div>
            </td>
        </div>
    </div>
    </form>
</main>
<script src="/assets/js/bootstrap5/bootstrap.min.js"></script>
</body>
</html>