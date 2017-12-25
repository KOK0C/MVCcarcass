<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 02.12.2017
 * Time: 19:56
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\TextFormat;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Comment;
use IhorRadchenko\App\Models\ForumSection;
use IhorRadchenko\App\Models\ForumTheme;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Forum extends Controller
{
    private $mainPage;
    private $leftSideBar;
    private $nav;

    /**
     * Forum constructor.
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function __construct()
    {
        $this->buildLeftSideBar();
        parent::__construct();
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/forum/main.phtml');
        $this->nav = new View('/App/templates/forum/nav.phtml');
        $this->mainPage->forums = ForumSection::getMainForumSection();
        View::display($this->header, $this->nav, $this->leftSideBar, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $parent
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionSection($page, string $parent)
    {
        $this->mainPage = new View('/App/templates/forum/forum.phtml');
        $this->nav = new View('/App/templates/forum/nav.phtml');
        $themes = ForumTheme::findByParentAlias($parent);
        if (! $themes) {
            throw new Error404();
        }
        $this->mainPage->totalPage = ceil(ForumTheme::getCountTheme($parent) / ForumTheme::PER_PAGE);
        $this->mainPage->forums = $themes;
        $this->nav->themes = $themes;
        View::display($this->header, $this->nav, $this->leftSideBar, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param $page
     * @param string $alias
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionTheme($page, string $alias)
    {
        $this->mainPage = new View('/App/templates/forum/theme.phtml');
        $this->nav = new View('/App/templates/forum/nav.phtml');
        $theme = ForumTheme::findByAlias($alias);
        if (! $theme) {
            throw new Error404();
        }
        $this->mainPage->theme = $theme;
        $this->nav->theme = $theme;
        $this->mainPage->forums = Comment::getForTheme($theme->getId());
        $this->mainPage->bbCode = new TextFormat();
        $this->mainPage->totalComments = ceil($theme->count / Comment::PER_PAGE);
        View::display($this->header, $this->nav, $this->leftSideBar, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param $page
     * @param string $alias
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAddTheme($page, string $alias)
    {
        $this->mainPage = new View('/App/templates/forum/add_theme.phtml');
        $this->nav = new View('/App/templates/forum/nav.phtml');
        $section = ForumSection::findByAlias($alias);
        if (! $section) {
            throw new Error404();
        }
        $this->mainPage->section = $section;
        $this->nav->section = $section;
        View::display($this->header, $this->nav, $this->leftSideBar, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCreateTheme()
    {
        if (Session::has('user') && $this->isPost('add_theme')) {
            $theme = new ForumTheme();
            $location = ForumSection::findById($_POST['parent_id'])->getAlias();
            $validRules = [
                'title' => [
                    'required' => true,
                    'minLength' => 2
                ],
                'text' => [
                    'required' => true
                ]
            ];
            if ($theme->load($_POST, $validRules)) {
                $theme->save();
                $comment = new Comment();
                if ($comment->load(array_merge($_POST, ['theme_id' => $theme->getId()]), $validRules)) {
                    $comment->save();
                    Redirect::to($location);
                }
                Redirect::to();
            }
            Redirect::to();
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    private function buildLeftSideBar()
    {
        $this->leftSideBar = new View('/App/templates/forum/forumSideBar.phtml');
        $this->leftSideBar->forums = ForumTheme::getLastRecord(5);
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxLoadTheme()
    {
        if ($this->isAjax() && isset($_POST['parent_id']) && isset($_POST['page'])) {
            View::loadForAjax('forum_themes', ForumTheme::getThemePerPage($_POST['page'], $_POST['parent_id']));
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxLoadComment()
    {
        if ($this->isAjax() && isset($_POST['theme']) && isset($_POST['page'])) {
            View::loadForAjax('forum_comments', Comment::getCommentsPerPage($_POST['page'], $_POST['theme']));
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxCreateComment()
    {
        if ($this->isAjax() && isset($_POST['theme_id']) && isset($_POST['text']) && Session::has('user')) {
            $validRules = [
                'text' => [
                    'required' => true
                ]
            ];
            if (isset($_POST['f_name'])) {
                $validRules['f_name'] = [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 30,
                ];
                $user = User::findById(Session::get('user')->getId());
                if ($user->load($_POST, $validRules)) {
                    $user->save();
                    Session::set('user', User::findById($user->getId()));
                } else {
                    exit();
                }
            }
            $comment = new Comment();
            if ($comment->load($_POST, $validRules)) {
                $comment->save();
                View::loadForAjax('forum_comments', [Comment::findById($comment->getId())]);
                exit();
            }
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxDeleteComment()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            $comment = Comment::findById($_POST['id']);
            if ($comment->delete()) {
                print 'true';
            }
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxShowUpdateComment()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            View::loadForAjax('update_comment', Comment::findById($_POST['id']));
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionAjaxUpdateComment()
    {
        if ($this->isAjax() && isset($_POST['idComment'])) {
            $data = Comment::findById($_POST['idComment']);
            if ($data->load($_POST, [
                'text' => [
                    'required' => true
                ]
            ])) {
                $data->save();
                View::loadForAjax('insert_update_comment', [Comment::findById($_POST['idComment']), (new TextFormat())]);
            }
            exit();
        }
        throw new Error404();
    }
}