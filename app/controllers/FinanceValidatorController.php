<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;

class FinanceValidatorController extends BaseController {

    public function __construct() {
        $this->config = Config::get('constant.module.finance.tabs');
        $this->others = Config::get('constant.others');
    }

    /**
     * This function is to get fields name from config file and set the rules
     * @param string $tab_name
     * @param boolean $sub_modules
     * @param string $sub_modules_prefix
     */
    public function getFieldWithRules($tab_name, $sub_modules = false, $sub_modules_prefix = '') {
        $validate_fields = [];
        $fields_name = [];
        if (empty($sub_modules_prefix) == false) {
            $main_fields_name = Arr::get($this->config[$tab_name], 'only');
            $sub_fields_name = Arr::get($this->config[$tab_name]['type'][$sub_modules_prefix], 'only');
            $fields_name = array_merge($main_fields_name, $sub_fields_name);
            // if mf, sf break out is_custom value
            // if(in_array($sub_modules_prefix,['mf'])) {
            //     $fields_name = array_diff( $fields_name,['is_custom'] );
            // }
        } else {
            $fields_name = Arr::get($this->config[$tab_name], 'only');
        }

        /**
         * Loop the config file and set the attributes and validation rules
         */
        foreach ($fields_name as $key) {
            $validate_key_name = '';
            $validate_rule = 'required|regex:/^-?\d*\.{0,1}\d+$/';
            if ($sub_modules) {
                $validate_key_name = $tab_name . '.' . $key;
                if (empty($sub_modules_prefix) == false) {
                    $validate_key_name = $tab_name . '.' . $sub_modules_prefix . '.' . $key;
                }
            } else {
                $validate_key_name = $tab_name . $key;
            }

            /**
             * Get special validation rules
             */
            if (empty(Arr::get($this->config[$tab_name], 'special_validation')) == false) {
                $sp_vld = Arr::get($this->config[$tab_name], 'special_validation');
                if (empty($sp_vld[$key]) == false) {
                    $validate_rule = $sp_vld[$key];
                }
            }
            $validate_fields[$validate_key_name] = $validate_rule;
        }
        return $validate_fields;
    }

    /**
     * This function is to get fields name from config file and set the rules
     * @param string $tab_name
     * @param boolean $sub_modules
     * @param string $sub_modules_prefix
     */
    public function getCustomMessages($tab_name, $sub_modules = false, $sub_modules_prefix = '') {

        $custom_messages = [];

        $fields_name = [];
        if (empty($sub_modules_prefix) == false) {
            $main_fields_name = Arr::get($this->config[$tab_name], 'only');
            $sub_fields_name = Arr::get($this->config[$tab_name]['type'][$sub_modules_prefix], 'only');
            $fields_name = array_merge($main_fields_name, $sub_fields_name);
        } else {
            $fields_name = Arr::get($this->config[$tab_name], 'message_fields');
        }
        /**
         * Loop the config file and set the attributes and messages
         */
        foreach ($fields_name as $key) {
            $message_key_name = $key;
            $message_key_value = $this->others['messages'][$key];
            if ($sub_modules) {
                if (empty($sub_modules_prefix) == false) {
                    $message_key_name = $tab_name . '.' . $sub_modules_prefix . '.' . $key;
                } else {
                    $message_key_name = $tab_name . '.' . $key;
                }
            }
            $custom_messages[$message_key_name] = $message_key_value;
        }
        return $custom_messages;
    }

    /**
     * This function is to get validation rules and message with the array data
     * @param string $tab_name
     * @param int $array_length
     * @param boolean $is_custom
     * @param string $sub_modules_prefix
     */
    public function getFieldWithRulesAndMessageInArray($tab_name, $array_length, $is_custom = false, $sub_modules_prefix = '') {

        $custom_messages = [];
        $validate_fields = [];
        $default = [];
        $fields_name = Arr::get($this->config[$tab_name], 'only');
        $validate_rule = 'required';
        $array_key = 'main';
        //check sub module prefix for validate
        if (empty($sub_modules_prefix) == false) {
            $default = Arr::get($this->config[$tab_name]['type'][$sub_modules_prefix], "default");
            //$validate_fields[$tab_name .'.'. $sub_modules_prefix] = $validate_rule;
            // $array_key = $sub_modules_prefix;
        } else {
            $default = Arr::get($this->config[$tab_name], "default");
        }

        if ($is_custom) {
            //     $extra_fields_name = Arr::get($this->config[$tab_name],'extra');
            //     $fields_name = array_merge($fields_name, $extra_fields_name);
            $fields_name = array_diff($fields_name, ['default']);
            $array_key = 'is_custom';
        } else {
            $fields_name = array_diff($fields_name, ['name']);
        }
        for ($i = 0; $i < $array_length; $i++) {

            foreach ($fields_name as $key) {

                $validate_key_name = "$tab_name.$array_key.$i.$key";
                $message_key_name = "$tab_name.$array_key.$i.$key";
                //get sub prefix validate key name
                if (empty($sub_modules_prefix) == false) {
                    $validate_key_name = "$tab_name.$sub_modules_prefix.$array_key.$i.$key";
                    $message_key_name = "$tab_name.$sub_modules_prefix.$array_key.$i.$key";
                }
                $validate_rule = "required|regex:/^\d+(\.\d{1,2})?$/";
                if (empty(Arr::get($this->config[$tab_name], 'special_validation')) == false) {
                    $sp_vld = Arr::get($this->config[$tab_name], 'special_validation');
                    if (empty($sp_vld[$key]) == false) {
                        $validate_rule = $sp_vld[$key];
                    }
                    if ($key == 'default') {
                        $where_in_default = implode(',', $default);
                        $validate_rule = $sp_vld[$key] . "|in:$where_in_default";
                    }
                }

                //get validate message
                $message_key_value = $this->others['messages'][$key];

                // if($sub_modules) {
                //     if(empty($sub_modules_prefix) == false) {
                //         $message_key_name = $tab_name . '.' . $sub_modules_prefix . '.' . $key;
                //     } else {
                //         $message_key_name = $tab_name . '.' . $key;
                //     }
                // }

                $custom_messages[$message_key_name] = $message_key_value;
                $validate_fields[$validate_key_name] = $validate_rule;

                $validate_rule = '';
            }
        }

        $response = [
            'custom_messages' => $custom_messages,
            'validate_fields' => $validate_fields,
        ];

        return $response;
    }

    /**
     * This function is to add finance file id check into the validation rules
     */
    public function addExtraValidationRules() {
        $response = [
            "validate_fields" => [
                'file_no'   => 'required',
                'year'      => 'required|numeric',
                'month'     => 'required|numeric',
                // "finance_file_id" => 'required'
            ],
            'custom_messages' => [
                'file_no'   => $this->others['messages']['file_no'],
                // "finance_file_id" => $this->others['messages']['finance_file_id']
            ]
        ];

        return $response;
    }

    public function validateFile($data, $is_update = false) {
        $validation_rules = $this->config['main']['special_validation'];
        $customMessages = $this->getCustomMessages('main');

        if ($is_update) {
            $validation_rules = [
                'file_no' => 'required',
                'year'      => 'required|numeric',
                'month'     => 'required|numeric',
            ];
        }

        $validator = Validator::make($data, $validation_rules, [], $customMessages);

        if ($validator->fails()) {
            return [
                'status' => 422,
                'data' => $validator->errors()->getMessages(),
                'message' => 'Validation Error'
            ];
        }

        return [
            'status' => 200
        ];
    }

    public function validateCheck($data, $finance_check = false) {
        $customMessages = $this->getCustomMessages('check', true);
        $validation_rules = [
            'check.date' => 'required|date_format:Y-m-d',
            'check.position' => 'required',
            'check.name' => 'required'
        ];
        if ($finance_check) {
            $add_extra_rules = $this->addExtraValidationRules();
            $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
            $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
        }
        
        $validator = Validator::make($data, $validation_rules, [], $customMessages);

        if ($validator->fails()) {
            return [
                'status' => 422,
                'data' => $validator->errors()->getMessages(),
                'message' => 'Validation Error'
            ];
        }

        return [
            'status' => 200
        ];
    }

    public function validateSummary($data, $finance_check = false) {
        $validation_rules = $this->getFieldWithRules('summary', true);
        $customMessages = $this->getCustomMessages('summary', true);

        if ($finance_check) {
            $add_extra_rules = $this->addExtraValidationRules();
            $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
            $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
        }

        $validator = Validator::make($data, $validation_rules, [], $customMessages);

        if ($validator->fails()) {
            return [
                'status' => 422,
                'data' => $validator->errors()->getMessages(),
                'message' => 'Validation Error'
            ];
        }

        return [
            'status' => 200
        ];
    }

    public function validateReport($data, $finance_check = false) {
        $key_check = ['mf', 'sf'];

        foreach ($key_check as $key) {
            $validation_rules = $this->getFieldWithRules('report', true, $key);
            $customMessages = $this->getCustomMessages('report', true, $key);
            if (empty(Arr::get($data['report'][$key], "is_custom")) == false) {
                $extra_validation = [];
                $extra_messages = [];

                if (is_array(Arr::get($data['report'][$key], "is_custom"))) {

                    foreach (Arr::get($data['report'][$key], "is_custom") as $key2 => $val2) {
                        $extra_validation = [
                            "report.$key.is_custom" => "required|array",
                            "report.$key.is_custom.$key2.name" => "required",
                            "report.$key.is_custom.$key2.amount" => "required|regex:/^\d+(\.\d{1,2})?$/",
                        ];
                        $extra_messages = [
                            "report.$key.is_custom.$key2.name" => "Name",
                            "report.$key.is_custom.$key2.amount" => "Amount",
                        ];
                    }
                    $validation_rules = array_merge($validation_rules, $extra_validation);
                    $customMessages = array_merge($customMessages, $extra_messages);
                }
                
                if (is_array(Arr::get($data['report'][$key], "extra"))) {

                    foreach (Arr::get($data['report'][$key], "extra") as $key2 => $val2) {
                        $extra_validation = [
                            "report.$key.extra" => "required|array",
                            "report.$key.extra.$key2.fee_sebulan" => "required|regex:/^\d+(\.\d{1,2})?$/",
                            "report.$key.extra.$key2.unit" => "required",
                            "report.$key.extra.$key2.fee_semasa" => "required|regex:/^\d+(\.\d{1,2})?$/",
                        ];
                        $extra_messages = [
                            "report.$key.extra.$key2.fee_sebulan" => "Fee Sebulan",
                            "report.$key.extra.$key2.unit" => "Unit",
                            "report.$key.extra.$key2.fee_semasa" => "Fee Semasa",
                        ];
                    }
                    $validation_rules = array_merge($validation_rules, $extra_validation);
                    $customMessages = array_merge($customMessages, $extra_messages);
                }
            }
            if ($finance_check) {
                $add_extra_rules = $this->addExtraValidationRules();
                $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
            }

            $validator = Validator::make($data, $validation_rules, [], $customMessages);

            if ($validator->fails()) {
                return [
                    'status' => 422,
                    'data' => $validator->errors()->getMessages(),
                    'message' => 'Validation Error'
                ];
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateIncome($data, $finance_check = false) {
        $key_check = ['main', 'is_custom'];
        foreach ($key_check as $key) {
            if (empty($data['income'][$key]) == false) {
                $is_custom = ($key == 'is_custom') ? true : false;
                $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('income', count($data['income'][$key]), $is_custom);

                $validation_rules = $main_validation_rules['validate_fields'];
                $customMessages = $main_validation_rules['custom_messages'];

                if ($finance_check) {
                    $add_extra_rules = $this->addExtraValidationRules();
                    $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                    $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
                }
                $validator = Validator::make($data, $validation_rules, [], $customMessages);

                if ($validator->fails()) {
                    return [
                        'status' => 422,
                        'data' => $validator->errors()->getMessages(),
                        'message' => 'Validation Error'
                    ];
                }
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateUtility($data, $finance_check = false) {
        $key_check = ['bhg_a', 'bhg_b'];
        foreach ($key_check as $key) {
            $validation_rules = [];
            $customMessages = [];
            $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('utility', count($data['utility'][$key]['main']), false, $key);
            $validation_rules = $main_validation_rules['validate_fields'];
            $customMessages = $main_validation_rules['custom_messages'];

            if (empty($data['utility'][$key]['is_custom']) == false) {
                $extra_validation_rules = $this->getFieldWithRulesAndMessageInArray('utility', count($data['utility'][$key]['is_custom']), true, $key);
                /**
                 * Merge is custom validation array with main validation array
                 */
                $validation_rules = array_merge($validation_rules, $extra_validation_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $extra_validation_rules['custom_messages']);
            }

            if ($finance_check) {
                $add_extra_rules = $this->addExtraValidationRules();
                $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
            }

            $validator = Validator::make($data, $validation_rules, [], $customMessages);

            if ($validator->fails()) {
                return [
                    'status' => 422,
                    'data' => $validator->errors()->getMessages(),
                    'message' => 'Validation Error'
                ];
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateContract($data, $finance_check = false) {
        $key_check = ['main', 'is_custom'];
        foreach ($key_check as $key) {
            if (empty($data['contract'][$key]) == false) {
                $is_custom = ($key == 'is_custom') ? true : false;
                $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('contract', count($data['contract'][$key]), $is_custom);

                $validation_rules = $main_validation_rules['validate_fields'];
                $customMessages = $main_validation_rules['custom_messages'];

                if ($finance_check) {
                    $add_extra_rules = $this->addExtraValidationRules();
                    $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                    $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
                }
                $validator = Validator::make($data, $validation_rules, [], $customMessages);

                if ($validator->fails()) {
                    return [
                        'status' => 422,
                        'data' => $validator->errors()->getMessages(),
                        'message' => 'Validation Error'
                    ];
                }
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateRepair($data, $finance_check = false) {
        $key_check = ['mf', 'sf'];
        foreach ($key_check as $key) {
            $validation_rules = [];
            $customMessages = [];
            $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('repair', count($data['repair'][$key]['main']), false, $key);
            $validation_rules = $main_validation_rules['validate_fields'];
            $customMessages = $main_validation_rules['custom_messages'];

            if (empty($data['repair'][$key]['is_custom']) == false) {
                $extra_validation_rules = $this->getFieldWithRulesAndMessageInArray('repair', count($data['repair'][$key]['is_custom']), true, $key);
                /**
                 * Merge is custom validation array with main validation array
                 */
                $validation_rules = array_merge($validation_rules, $extra_validation_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $extra_validation_rules['custom_messages']);
            }


            if ($finance_check) {
                $add_extra_rules = $this->addExtraValidationRules();
                $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
            }

            $validator = Validator::make($data, $validation_rules, [], $customMessages);

            if ($validator->fails()) {
                return [
                    'status' => 422,
                    'data' => $validator->errors()->getMessages(),
                    'message' => 'Validation Error'
                ];
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateVandal($data, $finance_check = false) {
        $key_check = ['mf', 'sf'];
        foreach ($key_check as $key) {
            $validation_rules = [];
            $customMessages = [];
            $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('vandal', count($data['vandal'][$key]['main']), false, $key);
            $validation_rules = $main_validation_rules['validate_fields'];
            $customMessages = $main_validation_rules['custom_messages'];

            if (empty($data['vandal'][$key]['is_custom']) == false) {
                $extra_validation_rules = $this->getFieldWithRulesAndMessageInArray('vandal', count($data['vandal'][$key]['is_custom']), true, $key);
                /**
                 * Merge is custom validation array with main validation array
                 */
                $validation_rules = array_merge($validation_rules, $extra_validation_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $extra_validation_rules['custom_messages']);
            }

            if ($finance_check) {
                $add_extra_rules = $this->addExtraValidationRules();
                $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
            }

            $validator = Validator::make($data, $validation_rules, [], $customMessages);

            if ($validator->fails()) {
                return [
                    'status' => 422,
                    'data' => $validator->errors()->getMessages(),
                    'message' => 'Validation Error'
                ];
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateStaff($data, $finance_check = false) {
        $key_check = ['main', 'is_custom'];
        foreach ($key_check as $key) {
            if (empty($data['staff'][$key]) == false) {
                $is_custom = ($key == 'is_custom') ? true : false;
                $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('staff', count($data['staff'][$key]), $is_custom);

                $validation_rules = $main_validation_rules['validate_fields'];
                $customMessages = $main_validation_rules['custom_messages'];

                if ($finance_check) {
                    $add_extra_rules = $this->addExtraValidationRules();
                    $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                    $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
                }

                $validator = Validator::make($data, $validation_rules, [], $customMessages);

                if ($validator->fails()) {
                    return [
                        'status' => 422,
                        'data' => $validator->errors()->getMessages(),
                        'message' => 'Validation Error'
                    ];
                }
            }
        }

        return [
            'status' => 200
        ];
    }

    public function validateAdmin($data, $finance_check = false) {
        $key_check = ['main', 'is_custom'];
        foreach ($key_check as $key) {
            if (empty($data['admin'][$key]) == false) {
                $is_custom = ($key == 'is_custom') ? true : false;
                $main_validation_rules = $this->getFieldWithRulesAndMessageInArray('admin', count($data['admin'][$key]), $is_custom);

                $validation_rules = $main_validation_rules['validate_fields'];
                $customMessages = $main_validation_rules['custom_messages'];


                if ($finance_check) {
                    $add_extra_rules = $this->addExtraValidationRules();
                    $validation_rules = array_merge($validation_rules, $add_extra_rules['validate_fields']);
                    $customMessages = array_merge($customMessages, $add_extra_rules['custom_messages']);
                }

                $validator = Validator::make($data, $validation_rules, [], $customMessages);

                if ($validator->fails()) {
                    return [
                        'status' => 422,
                        'data' => $validator->errors()->getMessages(),
                        'message' => 'Validation Error'
                    ];
                }
            }
        }

        return [
            'status' => 200
        ];
    }

}
