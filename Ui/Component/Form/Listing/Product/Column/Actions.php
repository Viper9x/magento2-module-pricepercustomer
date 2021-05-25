<?php

namespace TuVan\PricePerCustomer\Ui\Component\Form\Listing\Product\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Renderer ID for select product action
 */
class Actions extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $productOriginalPrice = (float) filter_var(
                    $item['price'],
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
                $item[$this->getData('name')]['select'] = [
                    'callback' => [
                        [
                            'provider' => 'price_per_customer_form.price_per_customer_form.'
                                . 'general.select_product_modal',
                            'target' => 'closeModal',
                        ],
                        [
                            'provider' => 'price_per_customer_form.price_per_customer_form.'
                                . 'general.select_product_modal.price_per_customer_product_listing',
                            'target' => 'selectProduct',
                            'params' => [
                                'entity_id' => $item['entity_id'],
                                'product_name' => $item['name'],
                                'product_sku' => $item['sku'],
                                'original_price' => $productOriginalPrice,
                            ],
                        ]
                    ],
                    'href' => '#',
                    'label' => __('Select')
                ];
            }
        }

        return $dataSource;
    }
}
