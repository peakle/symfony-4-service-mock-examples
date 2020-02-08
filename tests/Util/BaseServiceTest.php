<?php

declare(strict_types=1);

namespace App\Tests\Util;

use App\Service\FirstAdditionalService;
use App\Service\BaseService;
use App\Service\SecondAdditionalService;
use Closure;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseServiceTest extends WebTestCase
{
    public function testRealBehavior(): void
    {
        $client = self::createClient();

        $baseService = $client->getContainer()->get(BaseService::class);

        $this->assertEquals(FirstAdditionalService::REAL_VALUE, $baseService->handleFirst());
    }

    public function testFakeBehaviorWithManualInjection(): void
    {
        $client = self::createClient();

        // create mock
        $mockService = $this
            ->getMockBuilder(FirstAdditionalService::class)
            ->setMethods(['echo'])
            ->getMock();

        $mockService->expects($this->any())
            ->method('echo')
            ->willReturn(FirstAdditionalService::FAKE_VALUE);

        // init service
        $baseService = $client->getContainer()->get(BaseService::class);

        // replace real AdditionalService service by mock
        $this->changePrivateProperty($baseService, $mockService, 'additionalService');

        $this->assertEquals(FirstAdditionalService::FAKE_VALUE, $baseService->handleFirst());
    }

    public function testFakeBehaviorWithContainerInjection(): void
    {
        $client = self::createClient();

        // create mock
        $mockService = $this
            ->getMockBuilder(SecondAdditionalService::class)
            ->setMethods(['echo'])
            ->getMock();

        $mockService->expects($this->any())
            ->method('echo')
            ->willReturn(FirstAdditionalService::FAKE_VALUE);

        /** @var SecondAdditionalService $serviceSecond */
        $serviceSecond = $client->getContainer()->get('service_second');

        // check that `service_second` alias to real SecondAdditionalService service
        $this->assertSame(get_class($serviceSecond), SecondAdditionalService::class);

        // replace alias by mocked service in container
        //
        // Note: you can replace only public aliases in container
        $client->getContainer()->set('service_second', $mockService);

        //get test service
        $baseService = $client->getContainer()->get(BaseService::class);

        $this->assertEquals(FirstAdditionalService::FAKE_VALUE, $baseService->handleSecond());
    }

    /**
     * @param $baseClass
     * @param $newValue
     * @param string|null $propertyName
     * @param Closure|null $injectionClosure
     */
    public function changePrivateProperty(
        $baseClass,
        $newValue,
        string $propertyName = null,
        Closure $injectionClosure = null
    ): void {
        if ($propertyName === null && $injectionClosure === null) {
            return;
        }

        if ($propertyName && $injectionClosure === null) {
            $injectionClosure = function ($class, $value) use ($propertyName) {
                $class->$propertyName = $value;
            };
        }

        $injectionClosure = $injectionClosure->bindTo(null, $baseClass);
        $injectionClosure($baseClass, $newValue);
    }
}
