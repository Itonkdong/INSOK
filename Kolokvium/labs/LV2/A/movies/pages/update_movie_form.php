<?php


include "../../utils/helper_functions.php";

if (!isset($_GET["id"]))
{
    header("Location: index.php");
    exit();
}

$db = connectDatabase();
$id = $_GET["id"];

$stmt = $db->prepare("select * from movies m where m.id = :id");
$stmt->bindValue(":id", $id, SQLITE3_INTEGER);
$result = $stmt->execute();

$movie = $result->fetchArray(SQLITE3_ASSOC);

if (!$movie)
{
    header("Location: index.php");
    exit();
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Movie</title>
</head>
<body>

<form action="../handlers/update_movie.php" method="post">
    <input type="hidden" name="id" value="<?php echo $movie["id"]?>">
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo $movie["title"]?>" required>
    </div>
    <div>
        <label for="genre">Genre</label>
        <input type="text" id="genre" name="genre" value="<?php echo $movie["genre"]?>" required>
    </div>
    <div>
        <label for="year">Year</label>
        <input type="number" id="year" name="year" value="<?php echo $movie["year"]?>" required>
    </div>
    <button>Save Movie</button>
</form>

</body>
</html>
