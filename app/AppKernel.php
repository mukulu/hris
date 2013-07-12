<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new FOS\UserBundle\FOSUserBundle(),
        	new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
            new Hris\UserBundle\HrisUserBundle(),
            new Hris\DashboardBundle\HrisDashboardBundle(),
            new Hris\OrganisationunitBundle\HrisOrganisationunitBundle(),
            new Hris\FormBundle\HrisFormBundle(),
            new Hris\RecordsBundle\HrisRecordsBundle(),
            new Hris\DataQualityBundle\HrisDataQualityBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            /** @noinspection PhpUndefinedNamespaceInspection */
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            /** @noinspection PhpUndefinedNamespaceInspection */
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            /** @noinspection PhpUndefinedNamespaceInspection */
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
