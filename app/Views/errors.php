<!DOCTYPE html>
<html lang="en">
<?php
$errorType = get_error_message($data['status']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $errorType; ?></title>
    <style>
        .content {
            width: 700px;
            height: 250px;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            max-width: 100%;
            max-height: 100%;
            overflow: auto;
        }

        h1 {
            font-size: 5em;
            line-height: 0.5px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-weight: normal;
            color: grey;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            line-height: 1.5em;
            color: grey;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif line-height: 0.5em;
            font-weight: lighter;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="content">

        <h1><?php echo $data['status']; ?></h1>
        <h2><?php echo $data['message']; ?></h2>

    </div>
</body>

</html>