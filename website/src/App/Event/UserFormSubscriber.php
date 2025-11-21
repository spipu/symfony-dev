<?php

/**
 * This file is a demo file for Spipu Bundles
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Spipu\UiBundle\Entity\Form\Field;
use Spipu\UiBundle\Event\FormDefinitionEvent;
use Spipu\UiBundle\Exception\FormException;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Form Listener
 */
class UserFormSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormDefinitionEvent::PREFIX_NAME . 'user_new_password' => 'onOther',
            FormDefinitionEvent::PREFIX_NAME . 'user_password' => 'onOther',
            FormDefinitionEvent::PREFIX_NAME . 'user_creation' => 'onUser',
            FormDefinitionEvent::PREFIX_NAME . 'user_profile' => 'onUser',
            FormDefinitionEvent::PREFIX_NAME . 'user_admin' => 'onUser',
        ];
    }

    /**
     * @param FormDefinitionEvent $event
     * @return void
     */
    public function onOther(FormDefinitionEvent $event): void
    {
        $event->getFormDefinition()->setEntityClassName(User::class);
    }

    /**
     * @param FormDefinitionEvent $event
     * @return void
     * @throws FormException
     */
    public function onUser(FormDefinitionEvent $event): void
    {
        $this->onOther($event);

        $event->getFormDefinition()->getFieldSet('information')->addField(
            new Field(
                'middlename',
                Type\TextType::class,
                15,
                [
                    'label'    => 'spipu.user.field.middle_name',
                    'required' => false,
                    'trim'     => true
                ]
            )
        );
    }
}
