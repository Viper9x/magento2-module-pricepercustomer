<?php

namespace TuVan\PricePerCustomer\Ui\Component\Form\Listing\Customer\Column;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Renderer ID for select customer action
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
                $item[$this->getData('name')]['select'] = [
                    'callback' => [
                        [
                            'provider' => 'price_per_customer_form.price_per_customer_form.'
                                . 'general.select_customer_modal',
                            'target' => 'closeModal',
                        ],
                        [
                            'provider' => 'price_per_customer_form.price_per_customer_form.'
                                . 'general.select_customer_modal.price_per_customer_customer_listing',
                            'target' => 'selectCustomer',
                            'params' => [
                                'entity_id' => $item['entity_id'],
                                'customer_name' => $item['name'],
                                'customer_email' => $item['email'],
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
