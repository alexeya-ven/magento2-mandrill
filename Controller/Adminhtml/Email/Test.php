<?php
/**
 * Ebizmarts_Mandrill Magento JS component
 *
 * @category    Ebizmarts
 * @package     Ebizmarts_Mandrill
 * @author      Ebizmarts Team <info@ebizmarts.com>
 * @copyright   Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Ebizmarts\Mandrill\Controller\Adminhtml\Email;

use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Test extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var \Ebizmarts\Mandrill\Helper\Data
     */
    protected $_helper;
    /**
     * @var StoreManagerInterface 
     */
    protected $storeManager;

    /**
     * Test constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Ebizmarts\Mandrill\Helper\Data $helper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Ebizmarts\Mandrill\Helper\Data $helper,
        StoreManagerInterface $storeManager
    ) {

        parent::__construct($context);
        $this->_transportBuilder = $transportBuilder;
        $this->_helper = $helper;
        $this->storeManager = $storeManager;
    }

    public function execute()
    {
        $email      = $this->getRequest()->getParam('email');
        $template   = "mandrill_test_template";
        $this->_transportBuilder->setTemplateIdentifier($template);
        $this->_transportBuilder->setFrom($this->_helper->getTestSender());
        $this->_transportBuilder->addTo($email);
        $this->_transportBuilder->setTemplateVars([]);
        $this->_transportBuilder->setTemplateOptions(
            ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId()]
        );
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData(['error'=>0]);
        return $resultJson;
    }
}
