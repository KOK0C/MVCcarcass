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

    '^news$'         => ['controller' => 'Main', 'action' => 'news'],
    '^overviews$'    => ['controller' => 'Main', 'action' => 'overviews'],
    '^technologies$' => ['controller' => 'Main', 'action' => 'technologies'],
    '^tuning$'       => ['controller' => 'Main', 'action' => 'tuning'],
    '^useful$'       => ['controller' => 'Main', 'action' => 'useful'],
    '^$'             => ['controller' => 'Main', 'action' => 'index']
];