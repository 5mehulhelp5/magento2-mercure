<?php

declare(strict_types=1);

namespace MaxStan\Mercure\Service;

use Magento\Customer\Model\Session as CustomerSession;
use MaxStan\Mercure\Api\MercureHubInterface;
use MaxStan\Mercure\Api\MercureTopicsAuthorizationInterface;
use MaxStan\Mercure\Model\Config;
use Symfony\Component\Mercure\Hub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\FactoryTokenProvider;
use Symfony\Component\Mercure\Jwt\LcobucciFactory;

class MercureSymfonyHub implements MercureHubInterface
{
    private array $mercureHubs = [];

    public function __construct(
        private readonly Config $config,
        private readonly CustomerSession $customerSession,
        private readonly MercureTopicsAuthorizationInterface $mercureTopicsAuthorization
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getMercureHub(?int $customerId = null): HubInterface
    {
        if (isset($this->mercureHubs[$customerId])) {
            return $this->mercureHubs[$customerId];
        }

        $jwFactory = new LcobucciFactory($this->config->getPublisherJwtSecret());
        $provider = new FactoryTokenProvider(
            $jwFactory,
            publish: $this->mercureTopicsAuthorization->getAllowedPublishTopics($customerId),
        );
        $this->mercureHubs[$customerId] = new Hub($this->config->getHubUrl(), $provider);

        return $this->mercureHubs[$customerId];
    }

    /**
     * @inheritDoc
     */
    public function getCurrentUserMercureHub(): HubInterface
    {
        $customerId = $this->customerSession->getCustomerId() ?: null;

        return $this->getMercureHub($customerId);
    }
}
