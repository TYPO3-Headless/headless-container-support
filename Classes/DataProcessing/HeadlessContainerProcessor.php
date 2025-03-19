<?php

declare(strict_types=1);

namespace Fanor51\HeadlessContainerSupport\DataProcessing;

use B13\Container\DataProcessing\ContainerProcessor;
use FriendsOfTYPO3\Headless\DataProcessing\DataProcessingTrait;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class HeadlessContainerProcessor implements DataProcessorInterface
{
    use DataProcessingTrait;

    /**
     * @param ContentObjectRenderer $cObj
     * @param array                 $contentObjectConfiguration
     * @param array                 $processorConfiguration
     * @param array                 $processedData
     *
     * @return array
     * @throws \JsonException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {

        $processedData = GeneralUtility::makeInstance(ContainerProcessor::class)->process(
            $cObj,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        $as = $cObj->stdWrapValue('as', $processorConfiguration, 'children');
        $temp = [];
        foreach ($processedData[$as] as $contentElement) {
            $temp[] = \json_decode($contentElement['renderedContent'], true, 512, JSON_THROW_ON_ERROR);
        }
        $processedData[$as] = $temp;

        return $this->removeDataIfnotAppendInConfiguration($processorConfiguration, $processedData);
    }
}
