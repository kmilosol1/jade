<?php
namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Application\Service\ActivityManager;
use Application\Listener\ActivityListener;
use Zend\ServiceManager\Factory\FactoryInterface;

class ApplicationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(EntityManager::class);
        $am = $container->get(ActivityManager::class);
        $al = $container->get(ActivityListener::class);
        return new $requestedName($em, $am, $al);
    }
}