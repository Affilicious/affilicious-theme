<?php
namespace ProjektAffiliateTheme\Product\Detail;

use ProjektAffiliateTheme\Auxiliary\PostHelper;
use ProjektAffiliateTheme\Product\Field\Field;
use ProjektAffiliateTheme\Product\Field\FieldGroup;
use ProjektAffiliateTheme\Product\Field\FieldGroupFactory;
use Carbon_Fields\Container as CarbonContainer;
use Carbon_Fields\Field as CarbonField;
use ProjektAffiliateTheme\Product\Product;
use ProjektAffiliateTheme\Product\ProductFactory;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

class DetailGroupSetup
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var FieldGroupFactory
     */
    private $fieldGroupFactory;

    /**
     * @var DetailGroupFactory
     */
    private $detailGroupFactory;

    /**
     * Hook into the required Wordpress actions
     */
    public function __construct()
    {
        $this->productFactory = new ProductFactory();
        $this->fieldGroupFactory = new FieldGroupFactory();
        $this->detailGroupFactory = new DetailGroupFactory();

        add_action('init', array($this, 'render'), 4);
        add_action('added_post_meta', array($this, 'sanitize'), 10, 4);
        add_action('updated_post_meta', array($this, 'sanitize'), 10, 4);
    }

    /**
     * Render the product detail groups meta boxes
     */
    public function render()
    {
        $query = new \WP_Query(array(
            'post_type' => FieldGroup::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        if(!$query->have_posts()) {
            return;
        }

        while ($query->have_posts()) {
            $query->the_post();

            $fieldGroup = $this->fieldGroupFactory->create($query->post);
            $title = $fieldGroup->getTitle();

            if (empty($title)) {
                continue;
            }

            $fields = $fieldGroup->getFields();
            $details = array();
            if(!empty($fields)) {
                foreach ($fields as $field) {
                    $fieldId = sprintf(
                        Detail::ID_TEMPLATE,
                        $fieldGroup->getId(),
                        $field->getKey()
                    );

                    // Carbon doesn't contain a number field. That's why we have to convert into a text field
                    $type = $field->isNumber() ? Field::TYPE_TEXT : $field->getType();

                    $detail = CarbonField::make($type, $fieldId, $field->getLabel());

                    if ($field->hasDefaultValue()) {
                        $detail->default_value($field->getDefaultValue());
                    }

                    if ($field->getHelpText()) {
                        $detail->help_text($field->getHelpText());
                    }

                    $details[] = $detail;
                }
            }

            CarbonContainer::make('post_meta', $fieldGroup->getTitle())
                ->show_on_post_type('product')
                ->show_on_taxonomy_term($fieldGroup->getCategory(), Product::TAXONOMY)
                ->set_priority('default')
                ->add_fields($details);
        }
    }

    /**
     * @param int $metaId
     * @param int $postId
     * @param string $metaKey
     * @param string $metaValue
     */
    public function sanitize($metaId, $postId, $metaKey, $metaValue)
    {
        if(strpos($metaKey, '_at_product_details') === 0) {
            $post = PostHelper::getPost($postId);
            $product = $this->productFactory->create($post);

            $pattern = "/_at_product_details_(.+)_(.+)$/";
            if(preg_match($pattern, $metaKey, $matches)) {
                $detailGroupId = intval($matches[1]);
                $key = $matches[2];

                $detailType = null;
                $detailGroups = $product->getDetailGroups();
                foreach ($detailGroups as $detailGroup) {
                    if ($detailGroup->getId() === $detailGroupId) {
                        $details = $detailGroup->getDetails();
                        foreach ($details as $detail) {
                            if ($detail->getKey() === $key) {
                                $detailType = $detail->getType();
                                break;
                            }
                        }
                    }
                }

                if ($detailType === null) {
                    return;
                }

                // Remove whitespace from the beginning and end
                $metaValue = trim($metaValue);

                switch ($detailType) {
                    case Detail::TYPE_TEXT:
                        $metaValue = filter_var($metaValue, FILTER_SANITIZE_STRING);
                        break;
                    case Detail::TYPE_NUMBER:
                        $metaValue = str_replace(',', '.', $metaValue);
                        $metaValue = preg_replace("/[^0-9+-,.]/","", $metaValue);
                        $metaValue = floatval($metaValue);

                        break;
                    case Detail::TYPE_FILE:
                        $metaValue = filter_var($metaValue, FILTER_SANITIZE_NUMBER_INT);
                        break;
                    default:
                        break;
                }

                // Update the sanitized value
                update_post_meta($postId, $metaKey, $metaValue);
            }
        }
    }
}
