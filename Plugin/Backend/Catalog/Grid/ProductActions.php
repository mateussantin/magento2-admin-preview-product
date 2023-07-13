<?php
    /**
     * Copyright © Mateus. All rights reserved.
     * mateussantin.jr@gmail.com
     */

    namespace Mateus\AdminPreviewProduct\Plugin\Backend\Catalog\Grid;

    use Magento\Framework\View\Element\UiComponent\ContextInterface;
    use Magento\Framework\UrlInterface;
    use Magento\Store\Model\StoreManagerInterface;

    class ProductActions
    {
        protected $context;
        protected $urlBuilder;
        protected $frontendUrlBuilder;
        protected $storeManager;

        public function __construct(
            ContextInterface $context,
            UrlInterface $frontendUrlBuilder,
            StoreManagerInterface $storeManager
        )
        {
            $this->context = $context;
            $this->frontendUrlBuilder = $frontendUrlBuilder;
            $this->storeManager = $storeManager;
        }

        public function afterPrepareDataSource(
            \Magento\Catalog\Ui\Component\Listing\Columns\ProductActions $subject,
            array $dataSource
        ) {
            if (isset($dataSource['data']['items'])) {
                $storeId = $this->context->getFilterParam('store_id');
                $this->frontendUrlBuilder->setScope($storeId);

                foreach ($dataSource['data']['items'] as $key => $item) {
                    $dataSource['data']['items'][$key][$subject->getData('name')]['preview'] = [
                        'href' => $this->frontendUrlBuilder->getUrl(
                            'catalog/product/view',
                            [
                                'id' => $item['entity_id'],
                                '_current' => false,
                                '_nosid' => true,
                            ]
                        ),
                        'target' => '_blank',
                        'label' => __('Preview'),
                        'hidden' => false,
                    ];
                }
            }

            return $dataSource;
        }
    }
