<?php
return [
    'table' => 'notebook_book',
    'cols' => [
        // It is mandatory to have an "id" column.
        'id' => [
            'type' => \Pluf\Data\Schema::SEQUENCE,
            'primary' => true,
            // It is automatically added.
            'blank' => true,
            'editable' => false,
            'readable' => true
        ],
        'title' => [
            'type' => \Pluf\Data\Schema::VARCHAR,
            'size' => 100,
            'blank' => false,
            'editable' => false,
            'readable' => true
        ],
        'description' => [
            'type' => 'Text',
            'blank' => false,
            'editable' => false,
            'readable' => true
        ],
        'creation_dtime' => [
            'type' => 'Datetime',
            'blank' => true,
            'editable' => false,
            'readable' => true
        ],
//         'items' => [
//             'type' => \Pluf\Data\Schema::ONE_TO_MANY,
//             // 'joinProperty' => 'id',
//             'inverseJoinModel' => \Pluf\NoteBook\Item::class,
//             'inverseJoinProperty' => 'book_id'
//         ],
//         'tags' => [
//             'type' => \Pluf\Data\Schema::MANY_TO_MANY,
//             'joinProperty' => 'id',
//             'inverseJoinModel' => \Pluf\NoteBook\Tag::class,
//             'inverseJoinProperty' => 'id'
//         ]
    ],
    'views' => [
//         'nonEmpty' => [
//             'join' => [
//                 [
//                     'joinProperty' => 'id',
//                     'inverseJoinModel' => \Pluf\NoteBook\Item::class,
//                     'inverseJoinProperty' => 'book_id',
//                     'alias' => 'item',
//                     'type' => \Pluf\Data\Schema::INNER_JOIN
//                 ]
//             ],
//             'group' => [
//                 'item.book_id'
//             ],
//             'having' => [
//                 new \Pluf\Db\Expression('count(*) > 0')
//             ]
//         ],
//         'empty' => [
//             'join' => [
//                 [
//                     'joinProperty' => 'items',
//                     'alias' => 'item'
//                 ]
//             ],
//             'where' => [
//                 [
//                     'item.book_id',
//                     null
//                 ]
//             ]
//         ]
    ]
];