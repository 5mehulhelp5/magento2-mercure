<?php

declare(strict_types=1);

namespace MaxStan\Mercure\Service;

use MaxStan\Mercure\Api\MercureTopicsAuthorizationInterface;
use MaxStan\Mercure\Model\MercurePublishTopicsProvider;

readonly class MercureTopicsAuthorization implements MercureTopicsAuthorizationInterface
{
    public function __construct(
        private MercurePublishTopicsProvider $publishTopicProvidersPool
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getAllowedPublishTopics(?int $customerId = null): array
    {
        $topics = $this->publishTopicProvidersPool->getPublicTopics();
        if ($customerId) {
            $topics = [
                ...$topics,
                ...$this->publishTopicProvidersPool->getPrivateTopics($customerId)
            ];
        }

        return $topics;
    }
}
