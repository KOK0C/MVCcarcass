<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:52
 */

namespace App;

use App\Components\Magic;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;

/**
 * Class View
 * @package App
 * @property Category $categories
 * @property array $news Массив объектов Article
 * @property Article $article Объект
 * @property Brand $brands
 * @property array $cars Массив объектов Car
 * @property Page $page Объект Page
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
     * @param View[] ...$views
     */
    public static function display(View ...$views)
    {
        foreach ($views as $view) {
            print $view->render();
        }
    }
}