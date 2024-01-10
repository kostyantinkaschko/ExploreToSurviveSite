<?php
session_start(); // Почати сесію

// Перевірка, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Перенаправлення на сторінку входу
    exit();
}


$servername = "localhost";
$database = "site";
$username = "root";
$password = "!L-fKdz@9TR6i*b";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_record'])) {
        $header = $_POST["header"];
        $text = $_POST["text"];
        $date = $_POST["date"];

        // Вставка даних у таблицю "Last-update"
        $stmt1 = $conn->prepare("INSERT INTO `Last-update` (`header`, `text`, `date`) VALUES (:header, :text, :date)");
        $stmt1->bindParam(":header", $header);
        $stmt1->bindParam(":text", $text);
        $stmt1->bindParam(":date", $date);

        if ($stmt1->execute()) {
            // Зберігаємо успішну вставку у сесії
            $_SESSION['success_message'] = "Вся текстова інформація успішно додана до таблиці 'Last-update'.";
        } else {
            $_SESSION['error_message'] = "Помилка під час додавання текстової інформації: " . $stmt1->errorInfo()[2];
        }

        // Завантаження зображення
        $image = $_FILES["image"];
        $image_name = $image["name"];
        $image_type = $image["type"];
        $image_size = $image["size"];
        $image_data = file_get_contents($image["tmp_name"]);

        // Вставка даних у таблицю "photos"
        $stmt2 = $conn->prepare("INSERT INTO `photos` (`photo_name`, `photo_type`, `photo_size`, `photo_data`) VALUES (:name, :type, :size, :data)");
        $stmt2->bindParam(":name", $image_name);
        $stmt2->bindParam(":type", $image_type);
        $stmt2->bindParam(":size", $image_size);
        $stmt2->bindParam(":data", $image_data);

        if ($stmt2->execute()) {
            // Зберігаємо успішну вставку у сесії
            $_SESSION['success_message'] = "Все готово!";
        } else {
            $_SESSION['error_message'] = "Помилка під час завантаження інформації: " . $stmt2->errorInfo()[2];
        }

        // Перенаправлення користувача на іншу сторінку після вставки даних
        header("Location: updates.php");
        exit(); // Завершуємо виконання поточного скрипту
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Помилка підключення до бази даних: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/devicon.ico" type="image/x-icon">
    <title>Адмін-панель</title>
    <style>
        body {
            background: url(../img/bg.png);
            margin: 0;
        }

        textarea {
            resize: vertical;
        }

        .admin-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            padding: 20px;
            max-width: 960px;
            margin: 30px auto;
            padding: 27px 10px;
            background-color: rgba(225, 225, 225, 0.5);
            filter: drop-shadow(2px 4px 6px black);
            box-shadow: 0px 1px 1px 2px #2f2f30;
            border: 17px solid rgba(1, 0, 0, 0.5);
            border-radius: 15px;
        }

        input[type="file"] {
            width: 114px;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 14px;
            color: #fff;
            font-size: 16px;
            font-family: system-ui;
            background-color: #383333;
            border: none;
            outline: none;
        }

        input[type="submit"]:hover {
            filter: invert(1);
            box-shadow: 0px 1px 19px 0px #fff;
        }

        hr {
            border-radius: 45px;
            border: 3px solid #fff;
            filter: opacity(0.5);
        }

        .form {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px;
            max-width: 15rem;
            color: #fff;
            font-family: system-ui;
            border-right: 15px solid gainsboro;
            border-left: 15px solid gainsboro;
            margin-right: 14px;
            border-radius: 4%;
        }

        h1,
        h2,
        p {
            color: #fff;
            font-family: system-ui;
        }

        span {
            color: #5f5f5f;
        }


        .error-message {
            color: red;
            font-weight: bold;
        }

        .vignette {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            background: url(../img/vignette.png);
            background-size: 1960px;
            background-position: center;
            pointer-events: none;
            z-index: 100;
        }

        @media screen and (min-width: 870px) {
            .vignette {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="vignette"></div>
    <div class="admin-panel">
        <h1><span>
                <?php if ($_SESSION['username'] == "Smerchiks") {
                    echo ("Вітя");
                } else if ($_SESSION['username'] == "MEGASELL") {
                    echo ("Лох");
                } else {
                    echo ($_SESSION['username']);
                }
                ?>
            </span>, привіт:</h1>
        <h2>Додавання нового запису</h2>
        <?php
        // Виводимо повідомлення про успішну або неуспішну вставку, якщо вони є у сесії
        if (isset($_SESSION['success_message'])) {
            echo $_SESSION['success_message'] . "<br>";
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo $_SESSION['error_message'] . "<br>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <label for="header">Заголовок:</label>
            <input type="text" name="header" id="header" required>
            <label for="text">Текст:</label>
            <textarea name="text" id="text" required></textarea>
            <label for="date">Дата:</label>
            <input type="date" name="date" id="date" required>
            <label for="image">Зображення:</label>
            <input type="file" name="image" id="image" accept="image/*">
            <input type="submit" value="Додати запис" name="add_record">
        </form>
    </div>
</body>

</html>