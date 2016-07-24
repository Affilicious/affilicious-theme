<?php
namespace ProjektAffiliateTheme\Product;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

class ProductField
{
    const ID = 'at_product_group_field_id';
    const NAME = 'at_product_group_field_name';
    const TYPE = 'at_product_group_field_type';
    const DEFAULT_VALUE = 'at_product_group_field_default_value';
    const HELP_TEXT = 'at_product_group_field_help_text';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var null|string
     */
    private $defaultValue;

    /**
     * @var null|string
     */
    private $helpText;

    /**
     * Convert the raw fields into an object
     * @param array $raw
     * @return ProductField
     */
    public static function fromRaw($raw)
    {
        $field = new ProductField($raw[self::ID], $raw[self::NAME], $raw[self::TYPE]);

        $defaultValue = $raw[self::DEFAULT_VALUE];
        if(!empty($defaultValue)) {
            $field->setDefaultValue($defaultValue);
        }

        $helpText = $raw[self::HELP_TEXT];
        if(!empty($helpText)) {
            $field->setHelpText($helpText);
        }

        return $field;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $type
     */
    public function __construct($id, $name, $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type === 'number' ? 'text' : $this->type;
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
     * @param null|string $defaultValue
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
     * @param null|string $helpText
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
    }
}
