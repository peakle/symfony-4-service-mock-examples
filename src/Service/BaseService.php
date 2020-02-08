<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseService
{
    /**
     * @var FirstAdditionalService $additionalService
     */
    private $additionalService;

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @param FirstAdditionalService $additionalService
     * @param ContainerInterface $container
     */
    public function __construct(FirstAdditionalService $additionalService, ContainerInterface $container)
    {
        $this->additionalService = $additionalService;
        $this->container = $container;
    }

    public function handleFirst(): string
    {
        return $this->additionalService->echo();
    }

    public function handleSecond(): string
    {
        $service = $this->container->get('service_second');

        return $service->echo();
    }
}
