<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:47
 */

return [
    '^(forum)/theme/([a-z0-9-]+)$' => ['controller' => 'Forum', 'action' => 'theme'],
    '^forum/ajax/loadTheme$'       => ['controller' => 'Forum', 'action' => 'ajaxLoadTheme'],
    '^(forum)/([a-z0-9-]+)$'       => ['controller' => 'Forum', 'action' => 'section'],
    '^(forum)$'                    => ['controller' => 'Forum', 'action' => 'index'],

    '^check/email$' => ['controller' => 'Check', 'action' => 'email'],

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

    '^mark/([a-z-]+)/([a-z0-9-]+)$' => ['controller' => 'Cars', 'action' => 'oneModel'],

    '^mark/([a-z-]+)$' => ['controller' => 'Cars', 'action' => 'oneMark'],

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