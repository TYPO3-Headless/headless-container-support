<?php

declare(strict_types=1);

namespace Fanor51\HeadlessContainerSupport\DataProcessing;

use B13\Container\DataProcessing\ContainerProcessor;
use B13\Container\Domain\Factory\Exception;
use FriendsOfTYPO3\Headless\DataProcessing\DataProcessingTrait;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class HeadlessContainerProcessor extends ContainerProcessor
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
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }
        if ($processorConfiguration['contentId.'] ?? false) {
            $contentId = (int)$cObj->stdWrap($processorConfiguration['contentId'],
                $processorConfiguration['contentId.']);
        } elseif ($processorConfiguration['contentId'] ?? false) {
            $contentId = (int)$processorConfiguration['contentId'];
        } else {
            $contentId = (int)$cObj->data['uid'];
        }

        try {
            $container = $this->frontendContainerFactory->buildContainer($cObj, $this->context, $contentId);
        } catch (Exception $e) {
            // do nothing
            return $processedData;
        }

        if (empty($processorConfiguration['colPos']) && empty($processorConfiguration['colPos.'])) {
            $allColPos = $container->getChildrenColPos();
            foreach ($allColPos as $colPos) {
                $processedData = $this->processColPos(
                    $cObj,
                    $container,
                    $colPos,
                    'children_' . $colPos,
                    $processedData,
                    $processorConfiguration
                );
            }
        } else {
            if ($processorConfiguration['colPos.'] ?? null) {
                $colPos = (int)$cObj->stdWrap($processorConfiguration['colPos'], $processorConfiguration['colPos.']);
            } else {
                $colPos = (int)$processorConfiguration['colPos'];
            }
            $as = 'children';
            if ($processorConfiguration['as']) {
                $as = $processorConfiguration['as'];
            }

            $processedData = $this->processColPos(
                $cObj,
                $container,
                $colPos,
                $as,
                $processedData,
                $processorConfiguration
            );

            $temp = [];
            foreach ($processedData[$as] as $contentElement) {
                $temp[] = \json_decode($contentElement['renderedContent'], true, 512, JSON_THROW_ON_ERROR);
            }
            $processedData[$as] = $temp;
        }

        return $this->removeDataIfnotAppendInConfiguration($processorConfiguration, $processedData);
    }
}
