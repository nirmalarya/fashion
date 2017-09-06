<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 03/09/17
 * Time: 10:10 AM
 */

namespace ScandicDesi\NewsletterPopup\Plugin\Newsletter\Block;

use Magento\Framework\View\Element\BlockFactory;
use Magento\Framework\View\Element\Template;
use Magento\Newsletter\Block\Subscribe;
use ScandicDesi\NewsletterPopup\Block\Subscribe as NewsletterPopupSubscribe;

class NewsletterSubscribePlugin
{
    /** @var BlockFactory */
    private $blockFactory;

    public function __construct(
        BlockFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
    }

    /**
     * @param Subscribe $subject
     * @param $result
     * @return string
     */
    public function afterToHtml(Subscribe $subject, $result)
    {
        /** @var Template $newsletterPopupBlock */
        $newsletterPopupBlock = $this->blockFactory->createBlock(NewsletterPopupSubscribe::class);
        if ($subject->getTerminatePlugin() == false) {
            $result .= $newsletterPopupBlock->toHtml();
        }
        return $result;
    }
}