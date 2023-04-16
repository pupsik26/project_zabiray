<?php

require_once '../helpers/CurlAuthorizationHelper.php';

$curl = new CurlAuthorizationHelper();
$cards = $curl->getCards();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="send-notification">Заявка успешно отправлена</div>
    <div class="container">
        <div class="title indent"><b>Форма заявки</b></div>
        <div class="cards indent">
            <div class="select flex-padding">
                <label for="card-number">Номер карты</label>
                <select class="card-number" name="card-number" id="card-number">
                    <?php foreach ($cards as $key => $card): ?>

                        <option value=<?= $card['number'] ?>
                                <?= $key == count($cards) - 1 ? 'selected' : '' ?>
                                data-date=<?= $card['action_date'] ?>
                                data-tstmp=<?php
                                    $date = str_replace('/', '-','01-' . $card['action_date']);
                                    echo strtotime($date);
                                ?>>
                            <?= $card['number'] ?>
                        </option>

                    <?php endforeach; ?>
                </select>
            </div>

            <div class="time-card flex-padding" id="time-card">
                <label for="date">Дата окончания</label>
                <div id="date" data-nowTstmp="<?= time() ?>"
                     data-now="<?= date('m/Y') ?>">
                </div>
            </div>
        </div>
        <div class="notification hidden indent" id="notification">
        </div>
        <div class="btn">
            <button id="send" class="send" type="button">
                Отправить заявку
            </button>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.4.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
