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
    <section class="lot-item container">
        <h2>403 Доступ запрещен</h2>
        <p><?= $message ?></p>
    </section>
</main>

