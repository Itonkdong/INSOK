<?php
session_start();

require '../events/db_connection.php';
require 'jwt_helper.php';

$maxAttempts = 2;
$lockoutTime = 60;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['last_attempt_time']) && isset($_SESSION['attempt_count'])) {
        $timePassed = time() - $_SESSION['last_attempt_time'];

        if ($timePassed > $lockoutTime) {
            unset($_SESSION['attempt_count']); // Ресетирање на обидите
            unset($_SESSION['last_attempt_time']); // Ресетирање на времето на последниот обид
        }
    }

    // Ако има премногу обиди, се блокира понатамошна најава
//    if (isset($_SESSION['attempt_count']) && $_SESSION['attempt_count'] >= $maxAttempts) {
//        // Испишување на порака за грешка и прикажување на копче за повторно обидување
//        echo "Преголем број на обиди за најава.<br>";
//        echo "<a href='login.php'><button>Обидете се повторно</button></a>";
//        exit;
//    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    echo "{$user["password"]}";

    if ($user && password_verify($password, $user['password'])) {
        $token = createJWT($user['id'], $user['username'], $user['role']);

        session_regenerate_id(true);

        $_SESSION['jwt'] = $token;

        unset($_SESSION['attempt_count']);
        unset($_SESSION['last_attempt_time']);

        header('Location: ../events/index.php');
        exit;
    } else {
        if (!isset($_SESSION['attempt_count'])) {
            $_SESSION['attempt_count'] = 0;
        }

        $_SESSION['attempt_count']++;

        $_SESSION['last_attempt_time'] = time();

        // Ако се преминати дозволените обиди, се блокира понатамошен пристап
//        if ($_SESSION['attempt_count'] >= $maxAttempts) {
//            // Испишување на порака за грешка и прикажување на копче за повторно обидување
//            echo "Превисок број на обиди за најава.<br>";
//            echo "<a href='login.php'><button>Обидете се повторно</button></a>";
//            exit;
//        }

        echo "Invalid username or password.<br>";
        echo "<a href='login.php'><button>Try again</button></a>";
        exit;
    }
}
?>