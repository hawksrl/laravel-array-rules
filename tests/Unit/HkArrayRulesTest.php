<?php

namespace Tests\Unit;

use Hawk\ArrayRules\HkArrayRules;
use Tests\TestCase;

class HkArrayRulesTest extends TestCase
{
    public function testParseRules()
    {
        $arrayRules = [
            'value' => [
                'required' => 'This field is required',
                'min:6' => 'The value must be greather than 6',
            ],
        ];

        $parsedRules = HkArrayRules::parseRules($arrayRules);
        $parsedMessages = HkArrayRules::parseMessages($arrayRules);

        $this->assertEquals(
            $parsedRules,
            [
                'value' => 'required|min:6',
            ]
        );

        $this->assertEquals(
            $parsedMessages,
            [
                'value.required' => 'This field is required',
                'value.min' => 'The value must be greather than 6',
            ]
        );
    }

    public function testParseNestedRules()
    {
        $arrayRules = [
            'value' => [
                'child' => [
                    'required' => 'This field is required',
                    'min:6' => 'The value must be greather than 6',
                ],
            ],

            'items' => [
                '*' => [
                    'attribute' => [
                        'required' => 'This field is required',
                    ],
                ],
            ],
        ];

        $parsedRules = HkArrayRules::parseRules($arrayRules);
        $parsedMessages = HkArrayRules::parseMessages($arrayRules);

        $this->assertEquals(
            [
                'value.child' => 'required|min:6',
                'items.*.attribute' => 'required',
            ],
            $parsedRules
        );

        $this->assertEquals(
            [
                'value.child.required' => 'This field is required',
                'value.child.min' => 'The value must be greather than 6',
                'items.*.attribute.required' => 'This field is required',
            ],
            $parsedMessages
        );
    }

    //public function testParseMessages()
    //{
    //    $arrayRules = [
    //        'value' => [
    //            'required' => 'This field is required',
    //            'min:6' => 'The value must be greather than 6',
    //        ],
    //    ];
    //
    //    $parsedMessages = HkArrayRules::parseMessages($arrayRules);
    //
    //    $this->assertEquals(
    //        $parsedMessages,
    //        [
    //            'value.required' => 'This field is required',
    //            'value.min' => 'The value must be greather than 6',
    //        ]
    //    );
    //}
}
