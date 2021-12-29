<html>
    <head>
        <title>Check</title>
    </head>
    <?php
        $a = rand(0, 1000);
        echo "Value is  ".$a;
    ?>
    <body><br><br>
        <form>
        Enter a number : - <input name="name">
        <br><br>
        <input type="submit" name="submit" value="submit">
        </form>
        <?php
            if (isset($_POST['submit'])) {
                if ($_POST['name']==$a) {
                    echo 'yes';
                } else {
                    echo 'no';
                }
            }
        ?>
    </body>
</html>