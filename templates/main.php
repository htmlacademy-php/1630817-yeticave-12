<section class="lots container">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= strip_tags($lot['image']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= strip_tags($lot['category']); ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?lot_id=<?= strip_tags($lot['lot_id']); ?>"><?= strip_tags($lot['title']); ?></a>
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
</section>

