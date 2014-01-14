<?php

namespace Berichtsheft\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BerichtsheftBaseExtension extends Extension
{
  /**
   * {@inheritDoc}
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    $configuration = new Configuration();
    $config = $this->processConfiguration($configuration, $configs);

    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yml');

    if(isset($config['upgate_api_username']) && isset($config['upgate_api_password']))
    {
      $container->setParameter('berichtsheft_base.worklog_retriever.upgate.username', $config['upgate_api_username']);
      $container->setParameter('berichtsheft_base.worklog_retriever.upgate.password', $config['upgate_api_password']);
    }
  }
}
