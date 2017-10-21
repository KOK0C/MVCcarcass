<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:52
 */

namespace App;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;

/**
 * Class View
 * @package App
 * @property Category $categories
 * @property Article $news Массив объектов
 * @property Article $article Объект
 * @property Brand $brands
 */
class View
{
    use Magic;

    public $template;

    /**
     * View constructor.
     * @param string $link устанавливает путь к шаблону
     */
    public function __construct(string $link)
    {
        $this->template = $link;
    }

    /**
     * В цикле создаються переменные вида $array['key']=$value => $key=$value
     * @return string Возвращает строку с шаблоном
     */
    private function render()
    {
        ob_start();
        foreach ($this->_data as $name => $value) {
            $$name = $value;
        }
        include $_SERVER['DOCUMENT_ROOT'] . $this->template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param array $views Массив объектоа View
     */
    public static function display(array $views)
    {
        foreach ($views as $view) {
            print $view->render();
        }
    }
}