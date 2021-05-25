<?php

namespace TuVan\PricePerCustomer\Model\PricePerCustomer\Source;

/**
 * Provide option values for UI
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Represents an enabled Rule state.
     */
    const ENABLE_VALUE = 1;

    /**
     * Represents an disabled Rule state.
     */
    const DISABLE_VALUE = 0;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ENABLE_VALUE, 'label' => __('Enable')],
            ['value' => self::DISABLE_VALUE, 'label' => __('Disable')],
        ];
    }
}
