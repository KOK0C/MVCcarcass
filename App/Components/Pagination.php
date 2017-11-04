<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 04.11.2017
 * Time: 10:37
 */

namespace App\Components;


class Pagination
{
    /**
     *
     * @var int Ссылок навигации на страницу
     *
     */
    private $max = 4;

    /**
     * @var int Текущая страница
     */
    private $currentPage;

    /**
     * @var int Общее количество записей
     */
    private $total;

    /**
     * @var int Записей на страницу
     */
    private $limit;

    /**
     * Запуск необходимых данных для навигации
     * @param integer $total - общее количество записей
     * @param integer $limit - количество записей на страницу
     *
     */
    public function __construct($total, $currentPage, $limit)
    {
        # Устанавливаем общее количество записей
        $this->total = $total;

        # Устанавливаем количество записей на страницу
        $this->limit = $limit;

        # Устанавливаем количество страниц
        $this->amount = $this->amount();

        # Устанавливаем номер текущей страницы
        $this->setCurrentPage($currentPage);
    }

    /**
     *  Для вывода ссылок
     *
     * @return string HTML-код со ссылками навигации
     */
    public function get()
    {
        # Для записи ссылок
        $links = null;

        # Получаем ограничения для цикла
        $limits = $this->limits();

        $html = '<ul class="pagination">';
        # Генерируем ссылки
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
            # Если текущая это текущая страница, ссылки нет и добавляется класс active
            if ($page == $this->currentPage) {
                $links .= '<li class="btn pag-active"><span>' . $page . '</span></li>';
            } else {
                # Иначе генерируем ссылку
                $links .= $this->generateHtml($page);
            }
        }

        # Если ссылки создались
        if (!is_null($links)) {
            # Если текущая страница не первая
            if ($this->currentPage > 1)
                # Создаём ссылку "На первую"
                $links = $this->generateHtml(1, 'FIRST') . $links;

            # Если текущая страница не первая
            if ($this->currentPage < $this->amount)
                # Создаём ссылку "На последнюю"
                $links .= $this->generateHtml($this->amount, 'LAST');
        }

        $html .= $links . '</ul>';

        # Возвращаем html
        return $html;
    }

    /**
     * Для генерации HTML-кода ссылки
     * @param integer $page - номер страницы
     *
     * @return string
     */
    private function generateHtml($page, $text = null)
    {
        // Если текст ссылки не указан
        if (!$text)
            // Указываем, что текст - цифра страницы
            $text = $page;

        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);
        // Формируем HTML код ссылки и возвращаем
        return '<li><a href="' . $currentURI . 'page-' . $page . '" class = "btn">' . $text . '</a></li>';
    }

    /**
     *  Для получения, откуда стартовать
     * @return array массив с началом и концом отсчёта
     */
    private function limits()
    {
        // Вычисляем ссылки слева (чтобы активная ссылка была посередине)
        $left = $this->currentPage - round($this->max / 2);

        // Вычисляем начало отсчёта
        $start = $left > 0 ? $left : 1;

        // Если впереди есть как минимум $this->max страниц
        if ($start + $this->max <= $this->amount)
            // Назначаем конец цикла вперёд на $this->max страниц или просто на минимум
            $end = $start > 1 ? $start + $this->max : $this->max;
        else {
            // Конец - общее количество страниц
            $end = $this->amount;

            // Начало - минус $this->max от конца
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }

        return [$start, $end];
    }

    /**
     * Для установки текущей страницы
     */
    private function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        if ($this->currentPage > 0) {
            if ($this->currentPage > $this->amount)
                $this->currentPage = $this->amount;
        }
    }

    /**
     * Получение общего числа страниц
     * @return int число страниц
     */
    private function amount()
    {
        return ceil($this->total / $this->limit);
    }
}