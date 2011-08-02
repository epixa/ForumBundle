<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\Config\FileLocator;

/**
 * Exposes the forum bundle's services to the dependency injector
 *
 * @category   EpixaForumBundle
 * @package    DependencyInjection
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class EpixaForumExtension extends Extension
{
    /**
     * Loads the bundle's config for dependency injection
     * 
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('config.yml');
    }

    /**
     * Gets the unique alias for the bundle's dependencies
     * 
     * @return string
     */
    public function getAlias()
    {
        return 'epixa_forum';
    }
}