<?php

// Заголовок (название сайта покрашено в желтый)
echo  "Последние 5 новостей с портала \e[36m habr.com \e[0m" . "\n";

$url = "https://habr.com/ru/rss/all/all/";

if(@simplexml_load_file($url)){
    $feeds = simplexml_load_file($url);
}

$news_amount = 5;

for($i = 0; $i < $news_amount; $i++) {
    // Выбор элементов из xml
    $title = $feeds->channel->item[$i]->title;
    $link = $feeds->channel->item[$i]->link;
    $description = $feeds->channel->item[$i]->description;

    // Форматирование текста анонса поста
    $description = str_replace("\n", "", trim(strip_tags($description)));

    echo "\e[32m $title \e[0m" . "\n";
    echo $link . "\n";
    echo $description . "\n";
    echo "\e[35m ============================================== \e[0m" . "\n";
}
