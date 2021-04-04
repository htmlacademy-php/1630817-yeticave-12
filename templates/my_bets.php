<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= strip_tags($category['translation']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($my_bets as $bet) : ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img alt="<?= $bet['category']; ?>" height="40" src="<?= $bet['image']; ?>" width="54">
                        </div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?= $bet['lot_id'] ?>"> <?= $bet['title'] ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= $bet['category']; ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?= timer($bet['end_date'])['status'] ? 'timer--finishing' : ''; ?>"> <?= timer($bet['end_date'])['remaining_time']; ?></div>
                    </td>
                    <td class="rates__price">
                        <?= $bet['bet_sum']; ?>
                    </td>
                    <td class="rates__time">
                        <?= elapsed_time($bet['creation_date']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

