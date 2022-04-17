<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('start_date')
            ->add('end_date')
            ->add('nb_persons')
            ->add('price_event')
            ->add('imagefilename',FileType::class,[
                'mapped' => false,
                'required' => false,

                ])
            ->add('category',EntityType::class,[
                'label'=>false,
                'multiple'=>false,
                'expanded'=>false,
                'class'=>Category::class


            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
