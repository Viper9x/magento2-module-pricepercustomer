<?php

namespace TuVan\PricePerCustomer\Model\ResourceModel;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Model\AbstractModel;

/**
 * Price Per Customer Rule resource model
 */
class PricePerCustomer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('price_per_customer_rule', 'rule_id');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $rule
     * @return $this
     * @throws AlreadyExistsException
     */
    public function _beforeSave(AbstractModel $rule)
    {
        $connection = $this->getConnection();
        /** @var \TuVan\PricePerCustomer\Model\PricePerCustomer $rule */
        $bind = [
            'customer_id' => (int) $rule->getCustomerId(),
            'product_id' => (int) $rule->getProductId()
        ];
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where(
                'customer_id = :customer_id'
            )->where(
                'product_id = :product_id'
            );
        if ($rule->getId()) {
            $bind['rule_id'] = (int) $rule->getId();
            $select->where('rule_id != :rule_id');
        }

        $result = $connection->fetchOne($select, $bind);
        if ($result) {
            throw new AlreadyExistsException(
                __('A rule with the same properties (customer and product) already exists.')
            );
        }

        return $this;
    }
}
