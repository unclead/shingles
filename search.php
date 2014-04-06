<?php

set_include_path(dirname(__FILE__) . '/lib/');

require_once 'lib/SearchEngine.php';
require_once 'lib/ApiDataSource.php';

// подключение списка стоп-слов
$stop_words = require_once 'stop_words.php';

// список стоп-символов. В случае, если не указан система удалить, все кроме букв/цифр/пробела
$stop_symbols = '';

if (isset($_POST)) {
    $search_text = isset($_POST['search_text']) ? $_POST['search_text'] : null;

    if(empty($search_text)) {
        exit(400);
    }

    $url = strip_tags($_POST['url']);
    $token = strip_tags($_POST['token']);
    $limit = !empty($_POST['limit']) ? $_POST['limit'] : 100;

    // защита от "дурака"
    if($limit > 100000) {
        $limit = 100000;
    }

    $dataSource = new ApiDataSource();

    $dataSource->setUrl($url);
    $dataSource->setCount($limit);
    $dataSource->setToken($token);

    $options = array(
        'stop_words'        => $stop_words,
        'stop_symbols'      => $stop_symbols,
        'shingle_length'    => 10
    );

    $engine = new SearchEngine($dataSource, $options);
    $engine->setSearchText($search_text);

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = $engine->run(true);
        die($result);
    } else {
        $result = $engine->run();

        echo 'Количество дублей (совпадение 100%): ' . $result['duplicates'] . '<br/>';
        echo 'Процент схожести: ' . $result['percent'] . '<br/>';
        echo 'Затраченное время: ' . $result['time'] . '<br/>';
    }
}


