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
                    <a class="nav-link active" aria-current="page" href="/teachers">Преподаватели</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/subjects">Предметы</a>
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
    <h1>Создать преподавателя</h1>
    <form enctype="multipart/form-data" action="/teachers/createAction" method="post">
        {% if teacher %}
            <input type="hidden" name="teacherID" value="{{ teacher.id }}"/>
           <center> <img width="300" src="data:image/jpeg;base64,{{ teacher.avatar }}"/></center>
        {% endif %}
    <div class="mb-3">
        <label for="formFile" class="form-label">Загрузите фотографию</label>
        <input class="form-control" name="teacherAvatar" type="file" id="formFile"/>
    </div>
    <div class="row">
        <div class="col">
            <div class="row mb-3">
                <label for="colFormLabel" class="col-sm-2 col-form-label">ФИО</label>
                <div class="col-sm-10">
                    <input type="text" value="{{ teacher.name }}" class="form-control" name="teacherName" id="colFormLabel" placeholder="ФИО">
                </div>
            </div>
            <div class="row mb-3">
                <label for="colFormLabel" class="col-sm-2 col-form-label">Предметы</label>
                <div class="col-sm-10">
                    <select size="10" class="form-control" name="subjects[]" multiple="multiple">
                        {% for i, subject in subjects %}
                            <option value="{{ subject.id }}">{{ subject.title }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <td>
                <div class="d-grid gap-2">
                    {% if teacher %}
                        <button type="submit" class="btn btn-outline-dark" name="changeTeacherBtn">Изменить преподавателя</button>
                    {% else %}
                        <button type="submit" class="btn btn-outline-dark" name="createTeacherBtn">Добавить преподавателя</button>
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