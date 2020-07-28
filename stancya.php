<?php

/** Функция транслитерации названия станции метро */
function translit($s)
{
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод на следующую строку
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр
  $s = strtr($s, array(
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j',
    'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
    'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''
  ));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищае
  $s = str_replace(" ", "-", $s); // пробелы заменяем на тире
  return $s; // возвращаем полученный результат
}
/** в роутинге  {site_name}/stancya/{station_name_in_translit} - station_name_in_translit GET-параметр, выцепляем его  */
$name = $_GET["name"];
$xml = simplexml_load_file("data/metros.xml");
$xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
/** сравнение транслитерованого название станции и station_name_in_translit  */
foreach ($xml_array["location"] as &$value) {
  if (translit($value) == $name) {
    $station_name = $value;
  }
}
?>

<html>

<head>
  <meta charset="utf-8">
  <title>Ремонт одежды у метро <?= $station_name ?> </title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/kefir.css">
</head>

<body>
  <!-- Блок вывода информации  -->
  <div class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-md-2 col-sm-2 col-6">
          <img class="img-fluid" src="/data/moscow.png" alt="">
        </div>
        <div class="col-md-10 col-sm-10 col-offset-2 col-12">
          <h1>Ремонт одежды у метро <?= $station_name ?></h1>
          <b>Порвалась любимая кофта? Прожгли джинсовую ткань? Сломался замок на куртке? Нужен недорогой мастер в столице? Вы на верном пути! Что бы ни приключилось – специалисты ближайшей к Вам мастерской возле метро <?= $station_name ?> быстро справятся с неприятностью.</b>
          <p><a class="btn btn-primary btn-lg" href="/" role="button">На главную</a></p>
        </div>

      </div>
    </div>
  </div>
  <!-- Блок вывода всех станций метро в три колонки -->
  <div class="container">
    <div class="row" width="100%">
      <?php foreach ($xml_array["location"] as &$value) : ?>
        <div class="col-md-4">
          <a href="<?= translit($value); ?>"><?= $value; ?></a>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
  <script src="/js/jquery-3.5.1.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</body>

</html>