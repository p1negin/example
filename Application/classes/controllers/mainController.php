<?php

use Application\Classes\Utils\Mysql;
use Application\Classes\Utils\Router;
use Application\Classes\Utils\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class mainController
{
    /**
     * @var array
     */
    private array $params = [];

    /**
     *
     */
    public function __construct()
    {
        global $_SESSION;
        if (!isset($_SESSION['is_auth']) || $_SESSION['is_auth'] === false) {
            if (!in_array($_SERVER['REQUEST_URI'], ['/authAction', '/auth', '/reg', '/regAction'])) {
                @header('Location: /auth');
                die;
            }
        } else {
            if ($_SESSION['is_auth'] === true) {
                if (in_array($_SERVER['REQUEST_URI'], ['/authAction', '/auth', '/reg', '/regAction'])) {
                    @header('Location: /');
                    die;
                }
            }
        }
        $this->params = Router::getRoute()['params'];
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function reg(): void
    {
        $error_massage = null;
        if (isset($_POST['regBtn'])) {
            if (!isset($_POST['login']) || trim($_POST['login']) === '') {
                $error_massage = 'Введите логин';
            } else if (!isset($_POST['password']) || trim($_POST['password']) === '') {
                $error_massage = 'Введите пароль';
            } else if (!isset($_POST['repassword']) || trim($_POST['repassword']) === '') {
                $error_massage = 'Повторите пароль';
            } else if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 32) {
                $error_massage = 'Длина пароля от 6 до 32 символов';
            } else if ($_POST['password'] !== $_POST['repassword']) {
                $error_massage = 'Пароли не совпадают';
            } else {
                $stmt = Mysql::Db()->prepare('SELECT * FROM `users` WHERE `login` = ?');
                $stmt->execute([$_POST['login']]);
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    $error_massage = 'Пользователь с таким именем зарегистрирован на сервере';
                } else {
                    $stmt = Mysql::Db()->prepare('INSERT INTO `users` (`login`, `password`) VALUES (?, ?)');
                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->execute([$_POST['login'], $password_hash]);

                    $_SESSION['user'] = [
                        'login' => $_POST['login'],
                        'password' => $_POST['password']
                    ];
                    $_SESSION['is_auth'] = true;
                    @header('Location: /');
                    die;
                }
            }
            $this->regPage($error_massage);
        }
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function regPage(?string $errorMessage = null): void
    {
        $data['error'] = $errorMessage;
        echo Twig::$twig->render('reg.tpl', $data);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        @header('Location:/');
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function auth(): void
    {
        $error_massage = null;
        if (isset($_POST['authBtn'])) {
            if (!isset($_POST['login']) || trim($_POST['login']) === '') {
                $error_massage = 'Введите логин';
            } else if (!isset($_POST['password']) || trim($_POST['password']) === '') {
                $error_massage = 'Введите пароль';
            } else {
                $stmt = Mysql::Db()->prepare('SELECT * FROM `users` WHERE `login` = ?');
                $stmt->execute([$_POST['login']]);

                $result = $stmt->get_result();

                if (!$result->num_rows) {
                    $error_massage = 'Пользователь с таким именем не найден на сервере';
                } else {
                    $user = $result->fetch_array(MYSQLI_ASSOC);
                    $password_hash = $user['password'];
                    if (!password_verify($_POST['password'], $password_hash)) {
                        $error_massage = 'Неправильно введен пароль';
                    } else {
                        $_SESSION['is_auth'] = true;
                        $_SESSION['user'] = $user;
                        @header('Location: /');
                        die;
                    }
                }
            }
            $this->authPage($error_massage);
        }
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function authPage(?string $errorMessage = null): void
    {
        $data['error'] = $errorMessage;
        echo Twig::$twig->render('auth.tpl', $data);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function teachersPage(): void
    {
        $result = Mysql::Db()->query('SELECT * FROM `teachers`');

        $data = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $teacher) {
            $teacher['avatar'] = base64_encode($teacher['avatar']);
            $data['teachers'][] = $teacher;
        }
        $data['user'] = $_SESSION['user'];
        echo Twig::$twig->render('teachers.tpl', $data);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editTeacher(): void
    {
        $this->createTeacherPage(teacher: $this->params['teacher_id']);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editSubject(): void
    {
        $this->createSubjectPage(subject: $this->params['subject_id']);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createTeacher(): void
    {
        $error_massage = null;
        if (isset($_POST['createTeacherBtn'])) {
            if (!isset($_POST['teacherName']) || trim($_POST['teacherName']) === '') {
                $error_massage = 'Введите имя преподавателя!';
            } else if (!isset($_FILES['teacherAvatar']['tmp_name']) || trim($_FILES['teacherAvatar']['tmp_name']) === "") {
                $error_massage = 'Выберите изображение преподавателя!';
            } else {
                $blob_avatar = file_get_contents($_FILES['teacherAvatar']['tmp_name']);
            
              $name = $_POST['teacherName'];
  
              $teacher = Mysql::Db()->prepare('INSERT INTO `teachers` (`name`, `avatar`) VALUES (?, ?)');
              $teacher->execute([$name, $blob_avatar]);
  
              $subjects = $_POST['subjects'] ?? null;
  
              $id = $teacher->insert_id;
              if (is_array($subjects) && count($subjects)) {
                  foreach ($subjects as $subject) {
                      $subjects = Mysql::Db()->prepare('INSERT INTO `teachers_subjects` (`teacher_id`, `subject_id`) VALUES (?, ?)');
                      $subjects->execute([$id, $subject]);
                  }
              }
            }
        } else if (isset($_POST['changeTeacherBtn'])) {
            if (!isset($_POST['teacherName']) || trim($_POST['teacherName']) === '') {
                $error_massage = 'Введите имя преподавателя!';
            }
            $blob_avatar = null;
            $teacher_id = $_POST['teacherID'];

            $name = $_POST['teacherName'];
            if (isset($_FILES['teacherAvatar']['tmp_name']) && trim($_FILES['teacherAvatar']['tmp_name']) !== '') {
                $blob_avatar = file_get_contents($_FILES['teacherAvatar']['tmp_name']);
            }

            if ($blob_avatar !== null) {
                $teacher = Mysql::Db()->prepare('UPDATE `teachers` SET `name` = ?, `avatar` = ? WHERE `id` = ?');
                $teacher->execute([$name, $blob_avatar, $teacher_id]);
            } else {
                $teacher = Mysql::Db()->prepare('UPDATE `teachers` SET `name` = ? WHERE `id` = ?');
                $teacher->execute([$name, $teacher_id]);
            }

            $subjects = Mysql::Db()->prepare('DELETE FROM `teachers_subjects` WHERE `teacher_id` = ?');
            $subjects->execute([$teacher_id]);

            $subjects = $_POST['subjects'] ?? null;

            if (is_array($subjects) && count($subjects)) {
                foreach ($subjects as $subject_id) {
                    $s = Mysql::Db()->prepare('INSERT INTO `teachers_subjects` (`teacher_id`, `subject_id`) VALUES (?, ?)');
                    $s->execute([$teacher_id, $subject_id]);
                }
            }
        }

        if ($error_massage !== null) {
            $this->createTeacherPage($error_massage);
        } else {
            $this->teachersPage();
        }
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createSubject(): void
    {
        $error_massage = null;
        if (isset($_POST['createSubjectBtn'])) {
            if (!isset($_POST['subjectName']) || trim($_POST['subjectName']) === '') {
                $error_massage = 'Введите название предмета!';
            } else if (!isset($_POST['subjectDescription']) || trim($_POST['subjectDescription']) === '') {
                $error_massage = 'Введите описание предмета!';
            } else if (!isset($_POST['subjectTeachers']) || !count($_POST['subjectTeachers'])) {
                $error_massage = 'Выберите одного или нескольких преподавателей!';
            } else if (!isset($_FILES['subjectAvatar']['tmp_name']) || trim($_FILES['subjectAvatar']['tmp_name']) === '') {
                $error_massage = 'Выберите изображение предмета!';
            } else if (!isset($_POST['subjectDuration']) || !is_numeric($_POST['subjectDuration']) || $_POST['subjectDuration'] <= 0) {
                $error_massage = 'Продолжительность предмета должно быть в числовом формате!';
            } else {
              $blob_avatar = file_get_contents($_FILES['subjectAvatar']['tmp_name']);
              $title = $_POST['subjectName'];
              $description = $_POST['subjectDescription'];
              $duration = $_POST['subjectDuration'];
  
              $subject = Mysql::Db()->prepare('INSERT INTO `subjects` (`title`, `avatar`, `description`, `duration`) VALUES (?, ?, ?, ?)');
              $subject->execute([$title, $blob_avatar, $description, $duration]);
              $id = $subject->insert_id;
  
              foreach ($_POST['subjectTeachers'] as $teacher_id) {
                  $teacher = Mysql::Db()->prepare('INSERT INTO `teachers_subjects` (`teacher_id`, `subject_id`) VALUES (?, ?)');
                  $teacher->execute([$teacher_id, $id]);
              }
            }

        } else if (isset($_POST['changeSubjectBtn'])) {
            if (!isset($_POST['subjectName']) || trim($_POST['subjectName']) === '') {
                $error_massage = 'Введите название предмета!';
            } else if (!isset($_POST['subjectDescription']) || trim($_POST['subjectDescription']) === '') {
                $error_massage = 'Введите описание предмета!';
            } else if (!isset($_POST['subjectDuration']) || !is_numeric($_POST['subjectDuration']) || $_POST['subjectDuration'] <= 0) {
                $error_massage = 'Продолчительность предмета должно быть в числовом формате!';
            }

            $blob_avatar = null;
            if (isset($_FILES['subjectAvatar']['tmp_name']) && trim($_FILES['subjectAvatar']['tmp_name']) !== '') {
                $blob_avatar = file_get_contents($_FILES['subjectAvatar']['tmp_name']);
            }

            $subject_id = $_POST['subjectID'];
            $title = $_POST['subjectName'];
            $description = $_POST['subjectDescription'];
            $duration = $_POST['subjectDuration'];

            if ($blob_avatar !== null) {
                $teacher = Mysql::Db()->prepare('UPDATE `subjects` SET `title` = ?, `avatar` = ?, `description` = ?, `duration` = ? WHERE `id` = ?');
                $teacher->execute([$title, $blob_avatar, $description, $duration, $subject_id]);
            } else {
                $teacher = Mysql::Db()->prepare('UPDATE `subjects` SET  `title` = ?, `description` = ?, `duration` = ? WHERE `id` = ?');
                $teacher->execute([$title, $description, $duration, $subject_id]);
            }

            $subjects = Mysql::Db()->prepare('DELETE FROM `teachers_subjects` WHERE `subject_id` = ?');
            $subjects->execute([$subject_id]);

            $teachers = $_POST['subjectTeachers'] ?? null;

            if (is_array($teachers) && count($teachers)) {
                foreach ($teachers as $teacher_id) {
                    $s = Mysql::Db()->prepare('INSERT INTO `teachers_subjects` (`teacher_id`, `subject_id`) VALUES (?, ?)');
                    $s->execute([$teacher_id, $subject_id]);
                }
            }

        }

        if ($error_massage !== null) {
            $this->createSubjectPage($error_massage);
        } else {
            $this->subjectsPage();
        }
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function removeTeacher(): void
    {
        if (isset($this->params['teacher_id']) && is_numeric($this->params['teacher_id']) && $this->params['teacher_id'] > 0) {
            $result = Mysql::Db()->prepare('DELETE FROM `teachers` WHERE `id` = ?');
            $result->execute([$this->params['teacher_id']]);
        }
        $this->teachersPage();
    }

    /**
     * @param string|null $errorMessage
     * @param int|null $teacher
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createTeacherPage(?string $errorMessage = null, ?int $teacher = null): void
    {
        $data = [];
        if ($teacher !== null) {
            $result = Mysql::Db()->prepare('SELECT * FROM `teachers` WHERE `id` = ?');
            $result->execute([$teacher]);
            $data['teacher'] = $result->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            if (isset($data['teacher']['avatar'])) {
                $data['teacher']['avatar'] = base64_encode($data['teacher']['avatar']);
            }
        }
        $data['user'] = $_SESSION['user'];
        $data['subjects'] = Mysql::Db()->query('SELECT * FROM `subjects`')->fetch_all(MYSQLI_ASSOC);
        $data['error'] = $errorMessage;
        echo Twig::$twig->render('createTeacher.tpl', $data);
    }

    /**
     * @param string|null $errorMessage
     * @param int|null $subject
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createSubjectPage(?string $errorMessage = null, ?int $subject = null): void
    {
        $data = [];
        if ($subject !== null) {
            $result = Mysql::Db()->prepare('SELECT * FROM `subjects` WHERE `id` = ?');
            $result->execute([$subject]);
            $data['subject'] = $result->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            if (isset($data['subject']['avatar'])) {
                $data['subject']['avatar'] = base64_encode($data['subject']['avatar']);
            }
        }
        $data['user'] = $_SESSION['user'];
        $data['teachers'] = Mysql::Db()->query('SELECT * FROM `teachers`')->fetch_all(MYSQLI_ASSOC);
        $data['error'] = $errorMessage;
        echo Twig::$twig->render('createSubject.tpl', $data);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function subjectsPage(): void
    {
        $result = Mysql::Db()->query('SELECT * FROM `subjects`');

        $data = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $subject) {
            $subject['avatar'] = base64_encode($subject['avatar']);
            $data['subjects'][] = $subject;
        }
        $data['user'] = $_SESSION['user'];
        echo Twig::$twig->render('subjects.tpl', $data);
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function removeSubject(): void
    {
        if (isset($this->params['subject_id']) && is_numeric($this->params['subject_id']) && $this->params['subject_id'] > 0) {
            $result = Mysql::Db()->prepare('DELETE FROM `teachers_subjects` WHERE `subject_id` = ?');
            $result->execute([$this->params['subject_id']]);

            $result = Mysql::Db()->prepare('DELETE FROM `subjects` WHERE `id` = ?');
            $result->execute([$this->params['subject_id']]);
        }
        $this->subjectsPage();
    }
}