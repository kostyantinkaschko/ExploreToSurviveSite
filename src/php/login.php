<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/devicon.ico" type="image/x-icon">
    <title>Вхід</title>
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
        <h2>Вхід в акаунт</h2>
        <form class="form" method="post" action="login.php">
            <label for="username">Логін:</label>
            <input type="text" name="username" id="username" required><br>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" value="Увійти">
        </form>


        <?php
        session_start();

        // Параметри підключення до бази даних
        $servername = "localhost";
        $database = "site";
        $username = "root";
        $password = "!L-fKdz@9TR6i*b";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $password = $_POST["password"];
                $_SESSION['username'] = $_POST["username"];
                // Вибірка хешованого паролю з бази даних за логіном
                $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = :username");
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $hashedPasswordFromDB = $result['password_hash'];

                    if ($password == $hashedPasswordFromDB) {
                        // Логін та пароль вірні, створення сесії для авторизації
                        $_SESSION['user_id'] = $result['id'];
                        header("Location: dashboard.php"); // Перенаправлення на сторінку після входу
                        exit();
                    } else {
                        echo "<p>Невірний логін або пароль. Спробуйте ще раз.</p>";
                    }
                } else {
                    echo "<p>Користувача з таким логіном не знайдено.</p>";
                }
            }
        } catch (PDOException $e) {
            echo "<p>Помилка підключення до бази даних: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</body>

</html>