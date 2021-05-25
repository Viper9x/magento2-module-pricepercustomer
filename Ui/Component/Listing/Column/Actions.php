<?php

namespace TuVan\PricePerCustomer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class to build edit and delete link for each item.
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
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->context->getUrl(
                            'price_per_customer/index/edit',
                            ['rule_id' => $item['rule_id']]
                        ),
                        'label' => __('Edit'),
                        '__disableTmpl' => true,
                    ],
                    'delete' => [
                        'href' => $this->context->getUrl(
                            'price_per_customer/index/delete',
                            ['rule_id' => $item['rule_id']]
                        ),
                        'label' => 'Delete',
                        'confirm' => [
                            'title' => __('Delete rule'),
                            'message' => __('Are you sure you want to delete the rule?'),
                        ],
                        'post' => true
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
