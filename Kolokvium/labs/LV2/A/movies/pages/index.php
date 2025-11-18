<?php

include '../../utils/helper_functions.php';

$db = connectDatabase();

$stmt = <<<SQL
select * from movies
SQL;

$result = $db->query($stmt);


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movies</title>
</head>
<body>

<a href="add_movie_form.php">Add new Movie</a>

<table>
    <tr>
        <td>Title</td>
        <td>Genre</td>
        <td>Year</td>
        <td>Actions</td>
    </tr>

    <?php if ($result): ?>

        <?php while ($movie = $result->fetchArray(SQLITE3_ASSOC)): ?>

            <tr>
                <td><?php echo $movie["title"] ?></td>
                <td><?php echo $movie["genre"] ?></td>
                <td><?php echo $movie["year"] ?></td>
                <td>
                    <form method="get" action="update_movie_form.php" style="display: inline">
                        <input type="hidden" name="id" value="<?php echo $movie["id"]?>">
                        <button>Update</button>
                    </form>
                    <form method="post" action="../handlers/delete_movie.php" style="display: inline">
                        <input type="hidden" name="id" value="<?php echo $movie["id"]?>">
                        <button>Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php ?>

    <?php endif; ?>

    <tr>
        <td></td>
    </tr>

</table>

</body>
</html>