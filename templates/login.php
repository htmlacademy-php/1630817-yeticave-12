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
    <form action="./login.php" class="form container <?= ! empty(array_filter($errors)) ? 'form--invalid' : '' ?>"
          method="post">
        <h2>Вход</h2>
        <div class="form__item <?= ! empty($errors['email']) ? 'form__item--invalid' : '' ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" name="email" placeholder="Введите e-mail" type="text">
            <span class="form__error"><?= $errors['email'] ?? '' ?></span>
        </div>
        <div class="form__item form__item--last <?= ! empty($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" name="password" placeholder="Введите пароль" type="password">
            <span class="form__error"><?= $errors['password'] ?? '' ?></span>
        </div>
        <button class="button" type="submit">Войти</button>
    </form>
</main>
