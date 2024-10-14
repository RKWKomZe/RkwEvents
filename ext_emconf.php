<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rkw_events"
 *
 * Auto generated by Extension Builder 2015-07-15
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'RKW Events',
	'description' => 'Provides a possibility to list and handle events',
	'category' => 'plugin',
    'author' => 'Carlos Meyer, Maximilian Fäßler, Steffen Kroggel, Christian Dilger',
    'author_email' => 'cm@davitec.de, maximilian@faesslerweb.de, developer@steffenkroggel.de, c.dilger@addorange.de',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '1',
	'clearCacheOnLoad' => 0,
	'version' => '10.4.1',
	'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'core_extended' => '10.4.0-10.4.99',
            'dr_seo' => '10.4.0-12.4.99',
            'ajax_api' => '10.4.0-10.4.99',
            'postmaster' => '10.4.0-10.4.99',
            'static_info_tables' => '6.3.7',
            'rkw_basics' => '10.4.0-10.4.99',
            'fe_register' => '10.4.0-10.4.99',
        ],
		'conflicts' => [
		],
		'suggests' => [
            'sr_freecap' => '2.4.6-2.4.99',
        ],
	],
];
