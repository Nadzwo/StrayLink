<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/login.css">
    <link rel="icon" type="image" href="logo.png">
    <title>StrayLink</title>
</head>

<body>
    <div class="container">
        <section class="right-side">
            <div class="right-content">
                <h1>Selamat datang, <span class="user-rcon">Pahlawan!</span></h1>
                <p>Berani menyelamatkan, berani mencintai.</p>
            </div>
        </section>
        <section class="left-side">
            <div class="left-content">
                <img class="logo" src="Logo StrayLink.png" alt="Logo">                
                <form class="daftar-akun" method="post" action="">
                    <h2>Login</h2>
                    <input id="name"  name="nama" type="text" placeholder="Nama">
                    <input id="email" name="email" type="email" placeholder="Email">
                    <input id="password" name="password" type="password" placeholder="Password">
                                    <?php if (!empty($errors)): ?>
                    <div class="error-box">
                        <ul>
                            <?php foreach ($errors as $e): ?>
                                <li><?=  htmlspecialchars($e) ?></li>
                                <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                    <button>Masuk</button>

                    <p class="login-text">Belum punya akun? <a class="login-link" href="register.php">Daftar</a></p>
                </form>
            </div>
        </section>

    </div>
</body>
</html>