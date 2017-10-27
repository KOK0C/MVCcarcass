<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:47
 */

return [
    '^news/([0-9]+)$'         => ['controller' => 'Main', 'action' => 'onePage'],
    '^overviews/([0-9]+)$'    => ['controller' => 'Main', 'action' => 'onePage'],
    '^technologies/([0-9]+)$' => ['controller' => 'Main', 'action' => 'onePage'],
    '^tuning/([0-9]+)$'       => ['controller' => 'Main', 'action' => 'onePage'],
    '^useful/([0-9]+)$'       => ['controller' => 'Main', 'action' => 'onePage'],

    '^(news)$'         => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(overviews)$'    => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(technologies)$' => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(tuning)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^(useful)$'       => ['controller' => 'Main', 'action' => 'oneCategory'],
    '^$'               => ['controller' => 'Main', 'action' => 'index']
];