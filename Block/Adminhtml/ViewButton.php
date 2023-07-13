<?php
    /**
     * Copyright © Mateus. All rights reserved.
     * mateussantin.jr@gmail.com
     */

    namespace Mateus\AdminPreviewProduct\Block\Adminhtml;

    use Magento\Backend\Block\Widget\Container;
    use Magento\Backend\Block\Widget\Context;
    use Magento\Catalog\Model\Product;
    use Magento\Framework\App\Area;
    use Magento\Framework\Registry;
    use Magento\Store\Model\App\Emulation;

    class ViewButton extends Container
    {
        /**
         * @var Product
         */
        protected Product $_product;

        /**
         * Core registry
         *
         * @var Registry
         */
        protected ?Registry $_coreRegistry = null;

        /**
         * App Emulator
         *
         * @var Emulation
         */
        protected Emulation $_emulation;

        /**
         * @param Context $context
         * @param Registry $registry
         * @param Product $product
         * @param Emulation $emulation
         * @param array $data
         */
        public function __construct(
            Context $context,
            Registry $registry,
            Product $product,
            Emulation $emulation,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            $this->_product = $product;
            $this->_request = $context->getRequest();
            $this->_emulation = $emulation;
            parent::__construct($context, $data);
        }

        protected function _construct()
        {
            $this->addButton('preview_product', $this->getButton());
            parent::_construct();
        }

        public function getButton()
        {
            return [
                'label' => __('Preview'),
                'on_click' => sprintf("window.open('%s')", $this->_getProductUrl()),
                'class' => 'btn-preview-product',
                'sort_order' => 10
            ];
        }

        protected function _getProductUrl()
        {
            $store = $this->_request->getParam('store');

            if (!$store) {
                $this->_emulation->startEnvironmentEmulation(null, Area::AREA_FRONTEND, true);
                $productUrl = $this->_product->loadByAttribute(
                    'entity_id',
                    $this->_coreRegistry->registry('product')->getId()
                )->getProductUrl();
                $this->_emulation->stopEnvironmentEmulation();

                return $productUrl;
            }

            return false;
        }
    }
