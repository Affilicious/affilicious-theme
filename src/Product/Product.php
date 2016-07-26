<?php
namespace ProjektAffiliateTheme\Product;

use ProjektAffiliateTheme\Product\Detail\DetailGroup;
use ProjektAffiliateTheme\Product\Field\FieldGroup;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

class Product
{
    const POST_TYPE = 'product';
    const TAXONOMY = 'product_category';
    const SLUG = 'produktkategorie';

    /**
     * @var \WP_Post
     */
    private $post;

    /**
     * @var FieldGroup[]
     */
    private $fieldGroups;

    /**
     * @var DetailGroup[]
     */
    private $detailGroups;

    /**
     * @param \WP_Post $post
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
        $this->detailGroups = array();
        $this->fieldGroups = array();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->post->ID;
    }

    /**
     * Add a field group to the product
     * @param FieldGroup $fieldGroup
     */
    public function addFieldGroup(FieldGroup $fieldGroup)
    {
        $this->fieldGroups[$fieldGroup->getId()] = $fieldGroup;
    }

    /**
     * Remove a field group from the product by the given ID
     * @param int $id
     */
    public function removeFieldGroup($id)
    {
        unset($this->fieldGroups[$id]);
    }

    /**
     * Check if the product has the field group with the given ID
     * @param int $id
     */
    public function hasFieldGroup($id)
    {
        unset($this->fieldGroups[$id]);
    }

    /**
     * Get all field groups from the product
     * @return FieldGroup[]
     */
    public function getFieldGroups()
    {
        return $this->fieldGroups;
    }

    /**
     * Add a detail group to the product
     * @param DetailGroup $detailGroup
     */
    public function addDetailGroup(DetailGroup $detailGroup)
    {
        $this->detailGroups[$detailGroup->getId()] = $detailGroup;
    }

    /**
     * Remove a detail group from the product by the given ID
     * @param int $id
     */
    public function removeDetailGroup($id)
    {
        unset($this->detailGroups[$id]);
    }

    /**
     * Check if the product has the detail group with the given ID
     * @param int $id
     */
    public function hasDetailGroup($id)
    {
        unset($this->detailGroups[$id]);
    }

    /**
     * Get all detail groups from the product
     * @return DetailGroup[]
     */
    public function getDetailGroups()
    {
        return $this->detailGroups;
    }
}
