<?php

/**
 * Контроллер работы с элементами инфоблока
 *
 * Выполняет получение списка элементов инфоблока и их кэширование
 */
class InfoblockController {


    public function getData($sort, $filter, $fields){
        /**
         * Функция получения списка элементов инфоблока
         * @param array $sort Параметры сортировки
         * @param array $filter Параметры фильтрации
         * @param array $fields Список желаемых полей элемента
         * @return array
         */

        // Обьект кэша
        $cache = new CPHPCache();

        // Время кэширования
        $cache_lifetime = 3600;

        // Идентификатор кэша
        $cache_id = $filter['IBLOCK_ID'];

        // Директория кэша
        $cache_path = "/cache_test/";

        /* Описание логики кэширования
         *
         *   Если есть запись о списках инфоблока в кэше, то возвращаем 
         *   результат, который взят из кэша
         *   
         *
         *   Если записей о элементах инфоблока нет,
         *   то делаем запрос через CIBlockElement::GetList
         *   и кэшируем список элементов и возвращаем его
         *
         * */

        try {
            if ($cache->InitCache($cache_lifetime, $cache_id, $cache_path)) {
                // Код который берет список элементов из кэша
                $cache_vars = $cache->GetVars();

                return $cache_vars['result'];

            } elseif ($cache->StartDataCache()) {
                // Код результат которого будет закэширован

                // Подключение модуля инфоблока
                CModule::IncludeModule("iblock");

                // Обращение к API битрикса CIBlockElement::GetList
                $response = CIBlockElement::GetList(
                    $sort,
                    $filter,
                    false,
                    false,
                    $fields
                );

                // Заполнение массива элементами списка инфоблока
                $result = [];

                while ($element = $response->GetNext()) {
                    $result[] = $element;
                }

                // Запись в кэш
                $cache->EndDataCache(
                    [
                        "result" => $result
                    ]
                );

                return $result;
            }
        } catch (Exception $e) {
            echo 'Error: ' .  $e->getMessage() . "\n";
        }
    }
}