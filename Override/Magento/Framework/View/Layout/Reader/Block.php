<?php
namespace Siyu\BlockPatch\Override\Magento\Framework\View\Layout\Reader;

use Magento\Framework\App;
use Magento\Framework\Data\Argument\InterpreterInterface;
use Magento\Framework\View\Layout;


class Block extends \Magento\Framework\View\Layout\Reader\Block
{
	/**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    protected $scopeResolver;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param Layout\ScheduledStructure\Helper $helper
     * @param Layout\Argument\Parser $argumentParser
     * @param Layout\ReaderPool $readerPool
     * @param InterpreterInterface $argumentInterpreter
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\ScopeResolverInterface $scopeResolver
     * @param string|null $scopeType
     */
    public function __construct(
        Layout\ScheduledStructure\Helper $helper,
        Layout\Argument\Parser $argumentParser,
        Layout\ReaderPool $readerPool,
        InterpreterInterface $argumentInterpreter,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ScopeResolverInterface $scopeResolver,
        $scopeType = null
    ) {
        parent::__construct($helper,
            $argumentParser,
            $readerPool,
            $argumentInterpreter,
            $scopeType
        );
        $this->scopeConfig = $scopeConfig;
        $this->scopeResolver = $scopeResolver;
    }

    /**
     * scheduleReference
     *
     * Add ifconfig attribute to remove handler in reference block
     * @param  Layout\ScheduledStructure $scheduledStructure
     * @param  Layout\Element            $currentElement
     */
    protected function scheduleReference(
        Layout\ScheduledStructure $scheduledStructure,
        Layout\Element $currentElement
    ) {
        $elementName = $currentElement->getAttribute('name');
        $elementRemove = filter_var($currentElement->getAttribute('remove'), FILTER_VALIDATE_BOOLEAN);
        if ($elementRemove) {
            $configPath = (string)$currentElement->getAttribute('ifconfig');
            if (empty($configPath)
                || $this->scopeConfig->isSetFlag($configPath, $this->scopeType, $this->scopeResolver->getScope())
            ) {
                $scheduledStructure->setElementToRemoveList($elementName);
            }
        } else {
            $data = $scheduledStructure->getStructureElementData($elementName, []);
            $data['attributes'] = $this->mergeBlockAttributes($data, $currentElement);
            $this->updateScheduledData($currentElement, $data);
            $this->evaluateArguments($currentElement, $data);
            $scheduledStructure->setStructureElementData($elementName, $data);
        }
    }
}