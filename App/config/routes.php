<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:47
 */

return [
    '^mark/([a-z-]+)$' => ['controller' => 'Car', 'action' => 'oneMark'],

    '^(news)/([0-9]+)$'         => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(overviews)/([0-9]+)$'    => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(technologies)/([0-9]+)$' => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(tuning)/([0-9]+)$'       => ['controller' => 'Main', 'action' => 'oneArticle'],
    '^(useful)/([0-9]+)$'       => ['controller' => 'Main', 'action' => 'oneArticle'],

    '^(news)$'         => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(overviews)$'    => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(technologies)$' => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(tuning)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(useful)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^$'               => ['controller' => 'Main', 'action' => 'index']
];