<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:47
 */

return [
    '^create/article$'  => ['controller' => 'Admin\\CRUD\\Create', 'action' => 'article'],
    '^create/mark$'     => ['controller' => 'Admin\\CRUD\\Create', 'action' => 'mark'],
    '^create/car$'      => ['controller' => 'Admin\\CRUD\\Create', 'action' => 'car'],
    '^create/category$' => ['controller' => 'Admin\\CRUD\\Create', 'action' => 'category'],

    '^delete/article$'  => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'article'],
    '^delete/category$' => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'category'],
    '^delete/car$'      => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'car'],
    '^delete/mark$'     => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'mark'],
    '^delete/review$'   => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'review'],
    '^delete/user$'     => ['controller' => 'Admin\\CRUD\\Delete', 'action' => 'user'],

    '^update/article$'  => ['controller' => 'Admin\\CRUD\\Update', 'action' => 'article'],
    '^update/category$' => ['controller' => 'Admin\\CRUD\\Update', 'action' => 'category'],
    '^update/mark$'     => ['controller' => 'Admin\\CRUD\\Update', 'action' => 'mark'],
    '^update/car$'      => ['controller' => 'Admin\\CRUD\\Update', 'action' => 'car'],
    '^update/user$'     => ['controller' => 'Admin\\CRUD\\Update', 'action' => 'user'],

    '^(admin)/articles/create$'   => ['controller' => 'Admin\\Article', 'action' => 'create'],
    '^(admin)/articles/update$'   => ['controller' => 'Admin\\Article', 'action' => 'update'],
    '^(admin)/articles/([a-z]+)$' => ['controller' => 'Admin\\Article', 'action' => 'category'],
    '^(admin)/articles$'          => ['controller' => 'Admin\\Article', 'action' => 'index'],

    '^(admin)/categories/update$' => ['controller' => 'Admin\\Category', 'action' => 'update'],
    '^(admin)/categories$'        => ['controller' => 'Admin\\Category', 'action' => 'index'],

    '^(admin)/reviews/show$'                              => ['controller' => 'Admin\\Review', 'action' => 'show'],
    '^(admin)/reviews/mark/([a-z-]+)/model/([a-z0-9-]+)$' => ['controller' => 'Admin\\Review', 'action' => 'model'],
    '^(admin)/reviews/mark/([a-z-]+)$'                    => ['controller' => 'Admin\\Review', 'action' => 'mark'],
    '^(admin)/reviews$'                                   => ['controller' => 'Admin\\Review', 'action' => 'index'],

    '^(admin)/mark/update$'         => ['controller' => 'Admin\\Car', 'action' => 'updateMark'],
    '^(admin)/cars/update$'         => ['controller' => 'Admin\\Car', 'action' => 'updateCar'],
    '^(admin)/mark/create$'         => ['controller' => 'Admin\\Car', 'action' => 'createMark'],
    '^(admin)/cars/create$'         => ['controller' => 'Admin\\Car', 'action' => 'createCar'],
    '^(admin)/car/update$'          => ['controller' => 'Admin\\Car', 'action' => 'update'],
    '^(admin)/car/delete$'          => ['controller' => 'Admin\\Car', 'action' => 'delete'],
    '^(admin)/cars/mark/([a-z-]+)$' => ['controller' => 'Admin\\Car', 'action' => 'mark'],
    '^(admin)/cars$'                => ['controller' => 'Admin\\Car', 'action' => 'index'],

    '^(admin)/users/update$' => ['controller' => 'Admin\\User', 'action' => 'update'],
    '^(admin)/users$'        => ['controller' => 'Admin\\User', 'action' => 'index'],

    '^(admin)$' => ['controller' => 'Admin\\Main', 'action' => 'index'],

    '^search'                     => ['controller' => 'Main', 'action' => 'search'],

    '^forum/create/theme$'         => ['controller' => 'Forum', 'action' => 'createTheme'],
    '^(forum)/([a-z0-9-]+)/add$'   => ['controller' => 'Forum', 'action' => 'addTheme'],
    '^(forum)/theme/([a-z0-9-]+)$' => ['controller' => 'Forum', 'action' => 'theme'],
    '^forum/ajax/loadTheme$'       => ['controller' => 'Forum', 'action' => 'ajaxLoadTheme'],
    '^forum/ajax/loadComment$'     => ['controller' => 'Forum', 'action' => 'ajaxLoadComment'],
    '^forum/ajax/createComment$'   => ['controller' => 'Forum', 'action' => 'ajaxCreateComment'],
    '^(forum)/([a-z0-9-]+)$'       => ['controller' => 'Forum', 'action' => 'section'],
    '^(forum)$'                    => ['controller' => 'Forum', 'action' => 'index'],

    '^check/email$'        => ['controller' => 'Check', 'action' => 'email'],
    '^check/themeTitle$'   => ['controller' => 'Check', 'action' => 'themeTitle'],
    '^check/articleTitle$' => ['controller' => 'Check', 'action' => 'articleTitle'],
    '^check/phone$'        => ['controller' => 'Check', 'action' => 'phone'],
    '^check/mark$'         => ['controller' => 'Check', 'action' => 'mark'],
    '^check/car$'          => ['controller' => 'Check', 'action' => 'car'],
    '^check/category$'     => ['controller' => 'Check', 'action' => 'category'],

    '^review/create$'                               => ['controller' => 'Reviews', 'action' => 'create'],
    '^(reviews)/mark/([a-z-]+)/model/([a-z0-9-]+)$' => ['controller' => 'Reviews', 'action' => 'model'],
    '^(reviews)/mark/([a-z-]+)$'                    => ['controller' => 'Reviews', 'action' => 'mark'],
    '^(reviews)$'                                   => ['controller' => 'Reviews', 'action' => 'index'],

    '^(user)/profile$'         => ['controller' => 'User', 'action' => 'profile'],
    '^(user)/change_password$' => ['controller' => 'User', 'action' => 'changePassword'],
    '^(user)$'                 => ['controller' => 'User', 'action' => 'personalArea'],

    '^signup$' => ['controller' => 'User', 'action' => 'signUp'],
    '^login$'  => ['controller' => 'User', 'action' => 'logIn'],
    '^logout$' => ['controller' => 'User', 'action' => 'logOut'],

    '^mark/ajax/loadNews$'          => ['controller' => 'Cars', 'action' => 'showNews'],
    '^mark/([a-z-]+)/([a-z0-9-]+)$' => ['controller' => 'Cars', 'action' => 'oneModel'],
    '^mark/([a-z-]+)$'              => ['controller' => 'Cars', 'action' => 'oneMark'],

    '^(news)/page-([0-9]+)$'         => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(overviews)/page-([0-9]+)$'    => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(technologies)/page-([0-9]+)$' => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(tuning)/page-([0-9]+)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(useful)/page-([0-9]+)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],

    '^(news)/([a-z0-9-]+)$'         => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(overviews)/([a-z0-9-]+)$'    => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(technologies)/([a-z0-9-]+)$' => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(tuning)/([a-z0-9-]+)$'       => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(useful)/([a-z0-9-]+)$'       => ['controller' => 'Main', 'action' => 'oneArticle'],

    '^(news)$'         => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(overviews)$'    => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(technologies)$' => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(tuning)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(useful)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],

    '^$'               => ['controller' => 'Main', 'action' => 'index']
];