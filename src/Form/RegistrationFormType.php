<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", EmailType::class)
            ->add("pseudo", TextType::class)
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Match error",
                "required" => true,
                "constraints" => [
                    new NotBlank(),
                    new Length([
                        "min" => 6,
                        "minMessage" => "Vous devez renseigner au minimum {{ limit }} caractères"
                    ])
                ],
                "first_options" => ['label' => "Password"],
                "second_options" =>  ["label" => "Confirme Password"]
            ])
            ->add("agreeTerms", CheckboxType::class, [
                "mapped" => false,
                "constraints" => [
                    new IsTrue([
                        "message" => "Vous devez accepter mes conditions"
                    ])
                ]
            ])

        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}