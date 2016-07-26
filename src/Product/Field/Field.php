<?php
namespace ProjektAffiliateTheme\Product\Field;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

class Field
{
    const CARBON_KEY = 'key';
    const CARBON_TYPE = 'type';
    const CARBON_LABEL = 'label';
    const CARBON_DEFAULT_VALUE = 'default_value';
    const CARBON_HELP_TEXT = 'help_text';

    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_FILE = 'file';

    public static $types = array(
        self::TYPE_TEXT => self::TYPE_TEXT,
        self::TYPE_NUMBER => self::TYPE_NUMBER,
        self::TYPE_FILE => self::TYPE_FILE,
    );

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var null|string
     */
    private $defaultValue;

    /**
     * @var null|string
     */
    private $helpText;

    /**
     * @param string $key
     * @param string $type
     * @param string $label
     */
    public function __construct($key, $type, $label)
    {
        $this->key = $key;
        $this->type = $type;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->defaultValue !== null;
    }

    /**
     * @return null|string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return bool
     */
    public function hasHelpText()
    {
        return $this->helpText !== null;
    }

    /**
     * @return null|string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param string $helpText
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
    }
}
