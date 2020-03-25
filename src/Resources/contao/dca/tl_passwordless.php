<?php

$GLOBALS['TL_DCA']['tl_passwordless'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'username' => 'unique'
            )
        )
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL"
        ),
        'ttl' => array(
            'sql' => "int(10) unsigned NOT NULL"
        ),
        'username' => array
        (
            'sql' => "varchar(64) BINARY NOT NULL"
        ),
        'password' => array
        (
            'sql' => "text NOT NULL"
        ),
        'used' => array
        (
            'sql' => "char(1) NOT NULL default''"
        ),
    )
);
