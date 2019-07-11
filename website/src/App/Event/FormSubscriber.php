<?php declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Spipu\UiBundle\Entity\Form\Field;
use Spipu\UiBundle\Event\FormDefinitionEvent;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Form Listener
 */
class FormSubscriber implements EventSubscriberInterface
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
     * @throws \Spipu\UiBundle\Exception\FormException
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
