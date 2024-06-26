<!DOCTYPE html>
<html lang="en">
<?php
$errorType = get_error_message($data['status']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $errorType; ?></title>
    <link rel="stylesheet" href="/public/css/DefaultStyles.css">
</head>

<body>

    <div class="content">

        <h1><?php echo $data['status']; ?></h1>
        <h2><?php echo $data['message'] ? $data['message'] : $errorType; ?></h2>

    </div>

</body>

</html>
