<?php

namespace ScandicDesi\NewsletterPopup\Block;

use Magento\Cms\Block\Block as CmsBlock;
use Magento\Cms\Block\BlockFactory as CmsBlockFactory;
use Magento\Cms\Model\BlockRepository as CmsBlockRepo;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Framework\View\Element\Template;
use Magento\Newsletter\Block\Subscribe as NewsletterSubscribe;
use ScandicDesi\NewsletterPopup\Model\Config;

/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 03/09/17
 * Time: 9:36 AM
 */
class Subscribe extends Template
{
    /** @var Config */
    private $config;

    /** @var CmsBlockFactory */
    private $cmsBlockFactory;

    /** @var BlockFactory */
    private $blockFactory;

    /**
     * Subscribe constructor.
     * @param Template\Context $context
     * @param BlockFactory $blockFactory
     * @param CmsBlockFactory $cmsBlockFactory
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        BlockFactory $blockFactory,
        CmsBlockFactory $cmsBlockFactory,
        Config $config,
        array $data = []
    ) {
        $this->blockFactory = $blockFactory;
        $this->cmsBlockFactory = $cmsBlockFactory;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * Get the Left Banner Block content
     *
     * @param $identifier
     * @return string
     */
    public function getLeftBlock($identifier)
    {
        try {
            /** @var CmsBlock $leftBlock */
            $leftBlock = $this->cmsBlockFactory->create();
            $leftBlock->setBlockId($identifier);
            $html = $leftBlock->toHtml();
        } catch (\Exception $e) {
            $html = '';
        }
        return $html;
    }

    /**
     * Get the newsletter form content
     *
     * @return string
     */
    public function getNewsletterBlock()
    {
        /** @var Template $newsletterBlock */
        $newsletterBlock = $this->blockFactory->createBlock(NewsletterSubscribe::class);
        $newsletterBlock->setTemplate('Magento_Newsletter::subscribe.phtml')->setTerminatePlugin(true);
        return $newsletterBlock->toHtml();
    }

    /**
     * Get the newsletter popup delay time in seconds
     *
     * @return int
     */
    public function getPopupDelay()
    {
        if ($delay = $this->getData('popup_delay')) {
            return $delay;
        }
        return $this->config->getConfigValue('delay', 'popup');

    }

    /**
     * Is the popup visible in the current page
     *
     * @return bool
     */
    public function getIsVisible()
    {
        $data = $this->getData();
        return isset($data['is_visible']) ? $this->getData('is_visible') : true;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isEnabled() && $this->config->isPopupActive() && $this->getIsVisible()) {
            return parent::_toHtml();
        }
        return '';
    }
}