<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $key => $category): ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= strip_tags($category['translation']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form action="../login.php" class="form container" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" name="email" placeholder="Введите e-mail" type="text">
            <span class="form__error">Введите e-mail</span>
        </div>
        <div class="form__item form__item--last">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" name="password" placeholder="Введите пароль" type="password">
            <span class="form__error">Введите пароль</span>
        </div>
        <button class="button" type="submit">Войти</button>
    </form>
</main>
