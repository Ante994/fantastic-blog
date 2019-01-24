<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 23.01.19.
 * Time: 15:00
 */

namespace App\Form;

use App\Entity\PostTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostTranslationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titleEn', TextType::class)
            ->add('contentEn', TextareaType::class)
            ->add('titleHr', TextType::class)
            ->add('contentHr', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostTranslation::class,
        ]);

    }
}