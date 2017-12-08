<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:52
 */

namespace IhorRadchenko\App;

use IhorRadchenko\App\Components\Traits\Magic;
use IhorRadchenko\App\Components\Pagination;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Models\ForumSection;
use IhorRadchenko\App\Models\ForumTheme;
use IhorRadchenko\App\Models\Page;
use IhorRadchenko\App\Models\User;

/**
 * Class View
 * @package App
 * @property Category $categories
 * @property array $news Массив объектов Article
 * @property Article $article Объект
 * @property array $brands
 * @property array $cars Массив объектов Car
 * @property Page $page Объект Page
 * @property Brand $mark Объект Brand
 * @property Pagination $pagination
 * @property Car $car
 * @property User $user
 * @property array $reviews Review
 * @property array $forums
 * @property ForumTheme $theme
 * @property array $themes
 * @property ForumSection $section
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
        $content = ob_get_clean();
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

    public static function loadForAjax(string $view, $data)
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/ajax/' . $view . '.phtml';
    }
}