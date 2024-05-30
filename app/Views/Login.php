<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/DefaultStyles.css">
</head>
<body>
    <form class="content" action="/login" method="post">
  <div class="container">
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
  </div>
    </form>
    <?php if (isset($data['error'])) { ?>
        <p><?php echo $data['error'];?></p><br>
    <?php } ?>
</body>
</html>
