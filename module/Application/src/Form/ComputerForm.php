<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ComputerForm extends Form {

    private $translator;

    public function __construct($translator) {
        parent::__construct('computer-form');
        $this->translator = $translator;

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() {
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name'
            ],
        ]);
        $this->add([
            'type' => 'select',
            'name' => 'dimm',
            'attributes' => [
                'id' => 'dimm'
            ],
            'options' => [
                'label' => 'DIMM',
                'value_options' => [
                    'DDR SDRAM' => 'DDR SDRAM',
                    'DDR2 SDRAM' => 'DDR2 SDRAM',
                    'DDR3 SDRAM' => 'DDR3 SDRAM',
                    'DDR4 SDRAM' => 'DDR4 SDRAM',
                ]
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'description',
            'attributes' => [
                'id' => 'description'
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                ['name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            'isEmpty' => $this->translator->translate("Value is required and can't be empty"),
                        ],
                    ],
                ],
                ['name' => 'StringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 64,
                        'messages' => [
                            'stringLengthInvalid' => $this->translator->translate("Invalid type given. String expected"),
                            'stringLengthTooShort' => $this->translator->translate("The input is less than %min% characters long"),
                            'stringLengthTooLong' => $this->translator->translate("The input is more than %max% characters long"),
                        ],
                    ],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 1024,
                        'messages' => [
                            'stringLengthInvalid' => $this->translator->translate("Invalid type given. String expected"),
                            'stringLengthTooShort' => $this->translator->translate("The input is less than %min% characters long"),
                            'stringLengthTooLong' => $this->translator->translate("The input is more than %max% characters long"),
                        ],
                    ],
                ],
            ],
        ]);
    }

}
