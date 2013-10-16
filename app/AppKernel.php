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

            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new FOS\UserBundle\FOSUserBundle(),
            new FOS\MessageBundle\FOSMessageBundle(),
        	new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Hris\UserBundle\HrisUserBundle(),
            new Hris\MessageBundle\HrisMessageBundle(),
            new Hris\DashboardBundle\HrisDashboardBundle(),
            new Hris\OrganisationunitBundle\HrisOrganisationunitBundle(),
            new Hris\FormBundle\HrisFormBundle(),
            new Hris\RecordsBundle\HrisRecordsBundle(),
            new Hris\DataQualityBundle\HrisDataQualityBundle(),
            new Hris\IndicatorBundle\HrisIndicatorBundle(),
            new Hris\ReportsBundle\HrisReportsBundle(),
            new Hris\ImportExportBundle\HrisImportExportBundle(),
        	new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new Hris\HelpCentreBundle\HrisHelpCentreBundle(),
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
