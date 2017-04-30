<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\OAuthBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use WellCommerce\Bundle\AppBundle\Entity\Shop;

/**
 * Class OAuthDoctrineSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OAuthDoctrineSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->onShopDataBeforeSave($args);
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->onShopDataBeforeSave($args);
    }
    
    public function onShopDataBeforeSave(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Shop) {
            if (null === $entity->getFacebookConnectEnabled()) {
                $entity->setFacebookConnectEnabled(false);
            }

            if (null === $entity->getGoogleConnectEnabled()) {
                $entity->setGoogleConnectEnabled(false);
            }
        }
    }
}
