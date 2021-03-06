#Events 

Admin bundle provide several events that can be handled in application.
List of available events can be found in [CRUDEvents](/Event/CRUDEvents.php) and
[ResourceEvents](/Event/ResourceEvents.php). Listeners of ``CRUD_LIST_*`` events will receive one argument of
``FSi\Bundle\AdminBundle\Event\ListEvent`` class. Listeners of all the other events will receive one argument of
``FSi\Bundle\AdminBundle\Event\FormEvent`` class.

Following example will show you how to handle dynamically added/removed relation elements for doctrine entity.
Just like in http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-tags-to-be-removed
> This exsmple is only proof of concept, you should use orphanRemoval doctrine relation option instead of building complicated 
> event listener.

First you need to create event listener class

```php

<?php

namespace FSi\Bundle\DemoBundle\EventListener;

use FSi\Bundle\DemoBundle\Entity\News;
use FSi\Bundle\DemoBundle\Entity\Tag;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class CRUDEventListener
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    protected $registry;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->tags = array();
        $this->registry = $registry;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    public function crudEditEntityPreSubmit(FormEvent $event)
    {
        $entity = $event->getForm()->getData();

        if ($entity instanceof News) {
            $this->tags[$entity->getId()] = array();

            foreach ($entity->getTags() as $tag) {
                $this->tags[$entity->getId()][] = $tag;
            }
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    public function crudEditEntityPostSave(FormEvent $event)
    {
        $entity = $event->getForm()->getData();

        if ($entity instanceof News) {
            foreach ($entity->getTags() as $tag) {
                foreach ($this->tags[$entity->getId()] as $key => $toDel) {
                    if ($toDel->getId() === $tag->getId()) {
                        unset($this->tags[$entity->getId()][$key]);
                    }
                }
            }

            foreach ($this->tags[$entity->getId()] as $tag) {
                $this->registry->getManager()->remove($tag);
                $this->registry->getManager()->flush();
            }
        }
    }
}

```

Next and last step is event listener registration

```
<!-- src/FSi/Bundle/DemoBundle/Resources/config/services.xml --> 

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- Event Listeners -->
        <service id="fsi_demo_bundle.admin.listener.crud" class="FSi\Bundle\DemoBundle\EventListener\CRUDEventListener">
            <argument type="service" id="doctrine" />
            <tag name="kernel.event_listener" event="admin.crud.edit.form.request.pre_submit" method="crudEditEntityPreSubmit" />
            <tag name="kernel.event_listener" event="admin.crud.edit.entity.post_save" method="crudEditEntityPostSave" />
        </service>
    </services>
</container>
```

[Back to index](index.md)
