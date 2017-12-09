<?php

namespace AppBundle\Form;

use AppBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Task;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime();
        $currentYear = (int)$now->format('Y');
        $builder
            ->add('name', null, ['label' => 'Popis úkolu'])
            ->add('priority', ChoiceType::class, ['label' => 'Priorita', 'choices' => ['Nízká' => 0, 'Střední' => 1, 'Vysoká' => 2, 'Nejvyšší' => 3]])
            ->add('due', TextType::class, [
                'required' => false,
                'attr'     => [
                    'data-type' => 'datetime',
                ],
                'label' => 'Splnit do'
            ])
            ;

        $builder->get('due')->addModelTransformer(new CallbackTransformer(
            function (?\DateTime $datetime) {
                return ($datetime) ? $datetime->getTimestamp() : (new \DateTime())->getTimestamp();
            },
            function ($timestamp) {
                return (new \DateTime())->setTimestamp($timestamp);
            }));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_task';
    }


}
