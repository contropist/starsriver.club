<?php

$_config = [
    // ------------  CONFIG DB ------------ //
    'db'              => [
        '1'      => [
            'dbhost'    => 'localhost',
            'dbuser'    => 'sr',
            'dbname'    => 'starsriver.club',
            'dbpw'      => '123456',
            'dbcharset' => 'utf8',
            'tablepre'  => 'srclub_',
            'pconnect'  => '0',
        ],
        'slave'  => '',
        'common' => [
            'slave_except_table' => ''
        ],
    ],
    // ---------  CONFIG MEMORY  --------- //
    'memory'          => [
        'prefix'       => 'b6V0uQ_',
        'redis'        => [
            'server'      => '',
            'port'        => 6379,
            'pconnect'    => 1,
            'timeout'     => '0',
            'requirepass' => '',
            'serializer'  => 1,
        ],
        'memcache'     => [
            'server'   => '',
            'port'     => 11211,
            'pconnect' => 1,
            'timeout'  => 1,
        ],
        'file'         => [
            'server' => ''
        ],
        'apc'          => 1,
        'apcu'         => 1,
        'xcache'       => 1,
        'eaccelerator' => 1,
        'wincache'     => 1,
        'yac'          => 1,
    ],
    // ---------  CONFIG SERVER  --------- //
    'server'          => [
        'id' => 1,
    ],
    // --------  CONFIG DOWNLOAD  -------- //
    'download'        => [
        'readmod'   => 4,
        'xsendfile' => [
            'type' => '0',
            'dir'  => '/down/',
        ],
    ],
    // ---------  CONFIG OUTPUT  --------- //
    'output'          => [
        'charset'         => 'utf-8',
        'tpl_suffix'      => '.html',
        'forceheader'     => 1,
        'gzip'            => '0',
        'tplrefresh'      => 1,
        'language'        => 'zh_cn',
        'mobile'          => false,
        'staticurl'       => 'static/',
        'liburl'          => '//static.starscdn.com/repository/common',
        'fonturl'         => '//static.starscdn.com/repository/common/fonts',
        'imgurl'          => '//static.starscdn.com/repository/bbs.starsriver/img',
        'ajaxvalidate'    => '0',
        'upgradeinsecure' => '0',

    ],
    // ---------  CONFIG COOKIE  --------- //
    'cookie'          => [
        'cookiepre'    => '3Xuf_',
        'cookiedomain' => '',
        'cookiepath'   => '/',
    ],
    // --------  CONFIG SECURITY  -------- //
    'security'        => [
        'authkey'        => '3b9bf2b185292d40c12bae8154366ac9mNSiMkaHrEB3ZNTdwP',
        'urlxssdefend'   => 1,
        'attackevasive'  => '0',
        'onlyremoteaddr' => '0',
        'querysafe'      => [
            'status'    => 1,
            'dfunction' => [
                '0' => 'load_file',
                '1' => 'hex',
                '2' => 'substring',
                '3' => 'if',
                '4' => 'ord',
                '5' => 'char',
            ],
            'daction'   => [
                '0' => '@',
                '1' => 'intooutfile',
                '2' => 'intodumpfile',
                '3' => 'unionselect',
                '4' => '(select',
                '5' => 'unionall',
                '6' => 'uniondistinct',
            ],
            'dnote'     => [
                '0' => '/*',
                '1' => '*/',
                '2' => '#',
                '3' => '--',
                '4' => '"',
            ],
            'dlikehex'  => 1,
            'afullnote' => '0',
        ],
        'creditsafe'     => [
            'second' => '0',
            'times'  => 10,
        ],
        'fsockopensafe'  => [
            'port' => array(80),
        ],

    ],
    // ---------  CONFIG ADMINCP  --------- //
    'admincp'         => [
        'founder'      => '1',
        /* '1,2,3'... */
        'forcesecques' => '0',
        'checkip'      => 1,
        'runquery'     => '0',
        'dbimport'     => 1,
    ],
    // ----------  CONFIG REMOTE  --------- //
    'remote'          => [
        'on'     => '0',
        'dir'    => 'remote',
        'appkey' => '62cf0b3c3e6a4c9468e7216839721d8e',
        'cron'   => '0',
    ],
    // ----------  CONFIG INPUT  ---------- //
    'input'           => [
        'compatible' => 1,
    ],
    'plugindeveloper' => 1,
];

?>