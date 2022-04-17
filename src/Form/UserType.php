<?php

namespace App\Form;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'First Name'

                ],
                'required' => false,

            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Last Name'

                ],
                'required' => false,
            ])
            ->add('password', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Password',

                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Callback(function($object, ExecutionContextInterface $context) {
                        $password = strval($object);


                        if (strlen($password)<8) {
                            $context
                                ->buildViolation('Password must be at least 8 characters')
                                ->addViolation();
                        }

                    }),
                ],
                'required' => true,

            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'email@exemple.com'

                ],

                'required' => false,

            ])

            ->add('phone', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Phone Number',
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Callback(function($object, ExecutionContextInterface $context) {
                        $phone = strval($object);


                        if (strlen($phone)!=8) {
                            $context
                                ->buildViolation('Phone number must be 8 numbers')
                                ->addViolation();
                        }

                    }),
                ],
                'required' => true,

            ])
            ->add('identity', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Identity'

                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Callback(function($object, ExecutionContextInterface $context) {
                        $identity = strval($object);


                        if (strlen($identity)!=8) {
                            $context
                                ->buildViolation('Identity number must be 8 numbers')
                                ->addViolation();
                        }

                    }),
                ],
                'required' => false,

            ])

            ->add('birthday', DateType::class, [
                'attr' => [
                    'class' => 'form-control datepicker',
                    'placeholder'=>'dd/mm/yyyy'

                ],
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_CLIENT' => 'ROLE_CLIENT'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-style' => 'select-with-transition'
                ],
                'required' => true,
                'placeholder' => 'Select Role',
            ])

            ->add('pictureUser', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
                        'maxSizeMessage' => 'The file is too large. Allowed maximum size is 5 MiB.',
                        'uploadIniSizeErrorMessage' => 'The file is too large. Allowed maximum size is 5 MiB.',
                        'uploadFormSizeErrorMessage' => 'The file is too large. Allowed maximum size is 5 MiB.',
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
