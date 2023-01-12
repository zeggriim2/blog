<?php

namespace App\Form;

use App\DataTransformer\TagsTransformer;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

final class PostType extends AbstractType
{
    public function __construct(
        private TagsTransformer $tagsTransformer
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'empty_data' => '',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Article :',
                'empty_data' => '',
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags :',
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image(),
                    new NotNull([
                        'groups' => 'create',
                    ]),
                ],
            ])
        ;
        $builder->get('tags')->addModelTransformer($this->tagsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
