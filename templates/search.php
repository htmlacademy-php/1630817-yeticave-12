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
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search ?></span>»</h2>
            <?php if ( ! empty($lots)): ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot): ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= strip_tags($lot['image']); ?>" width="350" height="260" alt="">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= strip_tags($lot['category']); ?></span>
                                <h3 class="lot__title"><a class="text-link"
                                                          href="lot.php?lot_id=<?= strip_tags($lot['id']); ?>"><?= strip_tags($lot['title']); ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= strip_tags(format_price($lot['current_price'])); ?></span>
                                    </div>
                                    <div class="lot__timer timer   <?= timer($lot['end_date'])['status'] ? 'timer--finishing' : ''; ?>">
                                        <?= timer($lot['end_date'])['remaining_time']; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <h3> По заданному запросу ничего не найдено </h3>
            <?php  endif; ?>
        </section>
        <?php if ($page_count > 1 ): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev"><a <a href="./search.php?search=<?= strip_tags($search) ?>&page=<?= $page > 1  ? strip_tags($page - 1) : strip_tags($page)  ?> " >Назад</a></li>
                <?php for ($i = 0; $i < $page_count; $i++ ):?>
                <li class="pagination-item pagination-item-active"><a href="./search.php?search=<?= strip_tags($search) ?>&page=<?= $i+1 ?> "><?= $i+1 ?></a></li>
                <?php endfor; ?>
                <li class="pagination-item pagination-item-next"><a <a href="./search.php?search=<?= strip_tags($search) ?>&page=<?= $page < $page_count ? strip_tags($page + 1) : strip_tags($page) ?>">Вперед</a></li>
            </ul>
        <?php endif; ?>
    </div>
</main>

