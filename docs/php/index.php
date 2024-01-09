<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Explore To Survive - це фанатська гра по салкеру (Сталкер клікер) для телефонів на базі Android. Постапокаліптичний клікер зі сталкерською атмосферою та багатою геймплейною інтригою.">
    <meta name="keywords"
        content="Explore to Survive, сталкер клікер, сталкер на телефон, Morkovka Zet, Explore To Survive Коли обновлення, Explore To Survive що додалив обновленні?">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <title>Explore To Survive - обновлення, список доданих функцій</title>
    <style>
        header {
            background-color: #000;
        }

        .top-line {
            padding: 20rem 0;
        }

        @media screen and (min-width: 994px) {
            header {
                background-color: none;
            }

            .top-line {
                padding: 0;
            }
        }
    </style>
</head>

<body class="black-bg">
    <div class="vignette"></div>
    <div class="thirdpage">
        <header>
            <div class="top-line">
                <div class="wrapper">
                    <section class="rightside">
                        <div class="menu">
                            <nav>
                                <div class="border"></div>
                                <ul class="menu-list">
                                    <li>
                                        <a href="../index.html" class="home">H.O.M.E</a>
                                    </li>
                                    <li>
                                        <a href="../questions.html">F.A.Q</a>
                                    </li>
                                    <li>
                                        <a href="../policy.html">P.R.I.V.A.C.Y P.O.L.I.C.Y</a>
                                    </li>
                                    <li>
                                        <a href="updates.php">A.B.O.U.T U.P.D.A.T.E</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </section>
                </div>
            </div>
        </header>
        <main>
            <?php
            // Підключення до бази даних
            $servername = "localhost";
            $database = "site";
            $username = "root";
            $password = "!L-fKdz@9TR6i*b";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL-запит для вибору всіх записів з таблиці "Last-update" у порядку зменшення ID
                $stmt = $conn->query("SELECT * FROM `Last-update` ORDER BY id DESC");

                // Початковий номер блоку
                $blockNumber = 1;

                // Перебір записів та відображення їх на сторінці
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='block{$blockNumber}'>";
                    echo "<div class='wrapper'>";
                    echo "<div class='sides'>";
                    echo "<div class='leftside'>";
                    echo "<div class='version'>";
                    echo "<h2>{$row['header']}</h2>";
                    echo "</div>";
                    echo "<div class='block-text'>";
                    echo "<p>{$row['text']}</p>";
                    echo "<p>{$row['date']}</p>";
                    echo "</div>";
                    echo "</div>";

                    // SQL-запит для отримання фотографії за допомогоюid, який пов'язаний з записом
                    $photo_id = $row['id'];
                    $stmt2 = $conn->prepare("SELECT * FROM `photos` WHERE id = :id");
                    $stmt2->bindParam(":id", $photo_id);
                    $stmt2->execute();
                    $photo = $stmt2->fetch(PDO::FETCH_ASSOC);

                    if ($photo) {
                        // Відображення фотографії
                        echo "<div class='rightside'>";
                        echo "<img src='data:{$photo['photo_type']};base64," . base64_encode($photo['photo_data']) . "' alt='{$photo['photo_name']}' />";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";

                    // Збільшення номеру блоку на кожній ітерації
                    $blockNumber++;
                }
            } catch (PDOException $e) {
                echo "
                    <div class='rightside'>
                        <p class='error'>Помилка підключення до бази даних: " . $e->getMessage() . ". Сповістіть <a href='https://t.me/megaseII'>це розробникам сайту</a></p>
                    </div>
                ";
            }
            ?>
        </main>
        <footer>
            <div class="footer-last">
                <p>Games.Zet 2023</p>
            </div>
        </footer>
        <script src="../js/scripts.js"></script>
    </div>
</body>

</html>