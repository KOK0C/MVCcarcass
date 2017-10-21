<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:47
 */

return [
    '^news/([0-9]+)$'         => 'Main/onePage/$1',
    '^overviews/([0-9]+)$'    => 'Main/onePage/$1',
    '^technologies/([0-9]+)$' => 'Main/onePage/$1',
    '^tuning/([0-9]+)$'       => 'Main/onePage/$1',
    '^useful/([0-9]+)$'       => 'Main/onePage/$1',

    '^news$'         => 'Main/news',
    '^overviews$'    => 'Main/overviews',
    '^technologies$' => 'Main/technologies',
    '^tuning$'       => 'Main/tuning',
    '^useful$'       => 'Main/useful',
    ''               => 'Main/index',
];