<?php

namespace Tests\Unit\TakeawayMailer;

use App\TakeawayMailer\Exceptions\MessageValidationException;
use App\TakeawayMailer\Message;
use App\TakeawayMailer\ProviderFactory;
use Tests\TestCase;

class ProviderFactoryTest extends TestCase
{
    /**
     * Ensure ProviderFactory alternates between available providers
     */
    public function testProviderFactoryFallbackMechanism()
    {
        $providers_picked = [];

        $available_providers_count = count(ProviderFactory::$available_providers);

        for($i = 0; $i < $available_providers_count; $i++)
        {
            $providers_picked[] = ProviderFactory::make($i);
        }

        $this->assertEquals($available_providers_count, count($providers_picked));
    }
}
