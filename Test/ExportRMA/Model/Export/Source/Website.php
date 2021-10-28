<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Test\ExportRMA\Model\Export\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * @api
 * @since 100.0.2
 */
//class Website implements \Magento\Framework\Option\ArrayInterface
class Website extends AbstractSource
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * @return array
     */
/*    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = [];
            foreach ($this->_storeManager->getWebsites() as $website) {
                $id = $website->getId();
                $name = $website->getName();
                if ($id != 0) {
                    $this->_options[] = ['value' => $id, 'label' => $name];
                }
            }
        }
        var_dump($this->_options);
        die("To option array");
        return $this->_options;
    }
*/

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [];
            foreach ($this->_storeManager->getWebsites() as $website) {
                $id = $website->getId();
                $name = $website->getName();
                if ($id != 0) {
                    $this->_options[] = ['value' => $id, 'label' => $name];
                }
            }
        }
//        var_dump($this->_options);
//        die();
        return $this->_options;
    }
}
