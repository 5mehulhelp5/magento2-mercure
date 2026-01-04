<?php

declare(strict_types=1);

namespace MaxStan\Mercure\Api;

use Symfony\Component\Mercure\HubInterface;

/**
 * Provides Mercure Hub instances with user-specific authorization.
 *
 * This interface ensures users can only access topics they are authorized for
 * by generating appropriate JWT tokens based on user context.
 */
interface MercureHubInterface
{
    /**
     * Returns a hub with JWT token containing only topics the customer is authorized to publish.
     * If customer ID is null, returns topics for guests
     */
    public function getMercureHub(?int $customerId = null): HubInterface;

    /**
     * Get Mercure Hub configured for the currently authenticated user
     */
    public function getCurrentUserMercureHub(): HubInterface;
}
