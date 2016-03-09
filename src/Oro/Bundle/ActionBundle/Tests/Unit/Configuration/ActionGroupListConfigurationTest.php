<?php

namespace Oro\Bundle\ActionBundle\Tests\Unit\Configuration;

use Oro\Bundle\ActionBundle\Configuration\ActionGroupListConfiguration;

class ActionGroupListConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActionGroupListConfiguration
     */
    protected $configuration;

    public function setUp()
    {
        $this->configuration = new ActionGroupListConfiguration();
    }

    /**
     * @param array $config
     * @param array $expected
     *
     * @dataProvider processValidConfigurationProvider
     */
    public function testProcessValidConfiguration(array $config, array $expected)
    {
        $this->assertEquals($expected, $this->configuration->processConfiguration($config));
    }

    /**
     * @return array
     */
    public function processValidConfigurationProvider()
    {
        return [
            'empty configuration' => [
                'config' => [],
                'expected' => []
            ],
            'min valid configuration' => [
                'config' => [
                    'test' => []
                ],
                'expected' => [
                    'test' => [
                        'arguments' => [],
                        'conditions' => [],
                        'actions' => []
                    ]
                ]
            ],
            'max valid configuration' => [
                'config' => [
                    'test' => [
                        'acl_resource' => ['EDIT', new \stdClass()],
                        'arguments' => [
                            'arg1' => [
                                'type' => 'string',
                                'message' => 'Exception message',
                                'default' => 'test string',
                                'required' => true
                            ],
                            'arg2' => []
                        ],
                        'conditions' => [
                            '@equal' => ['a1', 'b1']
                        ],
                        'actions' => [
                            '@assign_value' => ['$field1', 'value2']
                        ]
                    ]
                ],
                'expected' => [
                    'test' => [
                        'acl_resource' => ['EDIT', new \stdClass()],
                        'arguments' => [
                            'arg1' => [
                                'type' => 'string',
                                'message' => 'Exception message',
                                'default' => 'test string',
                                'required' => true
                            ],
                            'arg2' => [
                                'required' => false
                            ]
                        ],
                        'conditions' => [
                            '@equal' => ['a1', 'b1']
                        ],
                        'actions' => [
                            '@assign_value' => ['$field1', 'value2']
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider processInvalidConfigurationProvider
     *
     * @param array $config
     * @param string $message
     */
    public function testProcessInvalidConfiguration(array $config, $message)
    {
        $this->setExpectedException(
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            $message
        );

        $this->configuration->processConfiguration($config);
    }

    /**
     * @return array
     */
    public function processInvalidConfigurationProvider()
    {
        return [
            'incorrect root' => [
                'config' => [
                    'group1' => 'not array value'
                ],
                'message' => 'Invalid type for path "action_groups.group1". Expected array, but got string'
            ],
            'incorrect action_groups[arguments]' => [
                'input' => [
                    'group1' => [
                        'arguments' => 'not array value'
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments". Expected array, but got string'
            ],
            'incorrect array action_groups[arguments]' => [
                'input' => [
                    'group1' => [
                        'arguments' => ['not array value']
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments.0". Expected array, but got string'
            ],
            'incorrect action_groups[arguments][type]' => [
                'input' => [
                    'group1' => [
                        'arguments' => [
                            'arg1' => [
                                'type' => []
                            ]
                        ]
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments.arg1.type". ' .
                    'Expected scalar, but got array'
            ],
            'incorrect action_groups[arguments][message]' => [
                'input' => [
                    'group1' => [
                        'arguments' => [
                            'arg1' => [
                                'message' => []
                            ]
                        ]
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments.arg1.message". ' .
                    'Expected scalar, but got array'
            ],
            'incorrect action_groups[arguments][default]' => [
                'input' => [
                    'group1' => [
                        'arguments' => [
                            'arg1' => [
                                'default' => []
                            ]
                        ]
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments.arg1.default". ' .
                    'Expected scalar, but got array'
            ],
            'incorrect action_groups[arguments][required]' => [
                'input' => [
                    'group1' => [
                        'arguments' => [
                            'arg1' => [
                                'required' => 'bool'
                            ]
                        ]
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.arguments.arg1.required". ' .
                    'Expected boolean, but got string'
            ],
            'incorrect action_groups[conditions]' => [
                'input' => [
                    'group1' => [
                        'conditions' => 'not array value'
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.conditions". Expected array, but got string'
            ],
            'incorrect action_groups[actions]' => [
                'input' => [
                    'group1' => [
                        'actions' => 'not array value'
                    ]
                ],
                'message' => 'Invalid type for path "action_groups.group1.actions". Expected array, but got string'
            ],
        ];
    }
}
