<?php
declare(strict_types = 1);

return [
    \RKW\RkwEvents\Domain\Model\Survey::class => [
        'tableName' => 'tx_rkwsurvey_domain_model_survey',
    ],
    \RKW\RkwEvents\Domain\Model\Department::class => [
        'tableName' => 'tx_rkwbasics_domain_model_department',
    ],
    \RKW\RkwEvents\Domain\Model\DocumentType::class => [
        'tableName' => 'tx_rkwbasics_domain_model_documenttype',
    ],
    \RKW\RkwEvents\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    \RKW\RkwEvents\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
    ],
    \RKW\RkwEvents\Domain\Model\BackendUserGroup::class => [
        'tableName' => 'be_groups',
    ],
    \RKW\RkwEvents\Domain\Model\File::class => [
        'tableName' => 'sys_file',
    ],
    \RKW\RkwEvents\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    \RKW\RkwEvents\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    \RKW\RkwEvents\Domain\Model\EventPlace::class => [
        'properties' => [
            'tstamp' => [
                'fieldName' => 'tstamp'
            ],
            'crdate' => [
                'fieldName' => 'crdate'
            ],
            'hidden' => [
                'fieldName' => 'hidden'
            ],
            'deleted' => [
                'fieldName' => 'deleted'
            ],
        ],
    ],
    \RKW\RkwEvents\Domain\Model\EventReservation::class => [
        'properties' => [
            'tstamp' => [
                'fieldName' => 'tstamp'
            ],
            'crdate' => [
                'fieldName' => 'crdate'
            ],
            'deleted' => [
                'fieldName' => 'deleted'
            ],
        ],
    ],
    \RKW\RkwEvents\Domain\Model\EventReservationAddPerson::class => [
        'properties' => [
            'tstamp' => [
                'fieldName' => 'tstamp'
            ],
            'crdate' => [
                'fieldName' => 'crdate'
            ],
            'deleted' => [
                'fieldName' => 'deleted'
            ],
        ],
    ],
    \RKW\RkwEvents\Domain\Model\Event::class => [
        'properties' => [
            'tstamp' => [
                'fieldName' => 'tstamp'
            ],
            'crdate' => [
                'fieldName' => 'crdate'
            ],
            'hidden' => [
                'fieldName' => 'hidden'
            ],
            'deleted' => [
                'fieldName' => 'deleted'
            ],
        ],
        'subclasses' => [
            '\RKW\RkwEvents\Domain\Model\EventScheduled' => \RKW\RkwEvents\Domain\Model\EventScheduled::class,
            '\RKW\RkwEvents\Domain\Model\EventAnnouncement' => \RKW\RkwEvents\Domain\Model\EventAnnouncement::class
        ]
    ],
    \RKW\RkwEvents\Domain\Model\EventScheduled::class => [
        'tableName' => 'tx_rkwevents_domain_model_event',
        'recordType' => '\RKW\RkwEvents\Domain\Model\EventScheduled'
    ],
    \RKW\RkwEvents\Domain\Model\EventAnnouncement::class => [
        'tableName' => 'tx_rkwevents_domain_model_event',
        'recordType' => '\RKW\RkwEvents\Domain\Model\EventAnnouncement'
    ],
];
