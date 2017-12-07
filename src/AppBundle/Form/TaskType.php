<?php

namespace AppBundle\Form;

use AppBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('name')
            ->add('priority', ChoiceType::class, ['choices' => ['Low' => 0, 'Medium' => 1, 'High' => 2, 'Top' => 3]])
            ->add('due', DateTimeType::class, ['years' => [$currentYear, $currentYear + 1, $currentYear + 2]])
            ->add('project')
            ->add('tags', EntityType::class, ['class' => Tag::class, 'multiple' => true, 'expanded' => 'true'])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class
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
