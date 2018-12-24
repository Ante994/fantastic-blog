<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 20.12.18.
 * Time: 11:18
 */

namespace App\Form;


use App\Entity\PostDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostDetailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostDetail::class,
        ]);
    }
}
