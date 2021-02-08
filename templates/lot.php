<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories  as $key => $category ): ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= strip_tags($category['translation']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?= strip_tags($lot['title']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src=" <?=  strip_tags($lot['image']); ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= strip_tags($lot['category']); ?></span></p>
                <p class="lot-item__description"><?= strip_tags($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer  <?=  strip_tags(timer($lot['creation_date'])['status']); ?>">
                        <?=  strip_tags(timer($lot['creation_date'])['remaining_time']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= strip_tags($lot['current_price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= strip_tags($lot['current_price'] + $lot['bet_step']); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet ): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= strip_tags($bet['login']); ?></td>
                                <td class="history__price"><?= strip_tags($bet['bet_sum']); ?></td>
                                <td class="history__time"><?= strip_tags(elapsed_time($bet['creation_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>


