<?php namespace Microblog\Form;

//use Microblog\DB\DB;

/**
 * The Validate class
 */
class Validate
{

    /**
     * The instance of the DB class
     *
     * @var \Microblog\DB\DB
     */
    //private $db = null;

    /**
     * The result of the last validation
     *
     * @var boolean
     */
    private $passed = false;

    /**
     * The validation errors stored in an array
     *
     * @var array
     */
    private $errors = [];

    /**
     * Get and set the single instance of the DB class
     */
    /*
    public function __construct(DB $db)
    {
        $this->db = $db;
    }
     * 
     */

    /**
     * Check if the given source is valid according the validation rules (stored as key => value pairs in the item array)
     * 
     * @param array $source The data to validate against
     * @param array $items The items and their respective validation rules
     * @return \Microblog\Form\Validate
     */
    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                $item = e($item);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if ($value !== $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}.");
                            }
                            break;
                            /*
                        case 'unique':
                            $check = $this->db->get($rule_value, [$item, '=', $value]);

                            if ($check->count()) {
                                $this->addError("{$item} already exists.");
                            }

                            break;
                             * 
                             */
                    }
                }
            }
        }

        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    /**
     * Add error to the array of errors
     * 
     * @param string $error
     */
    private function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Get the errors of the last validation
     * 
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Check if last validation passed
     * 
     * @return boolean
     */
    public function passed()
    {
        return $this->passed;
    }
}