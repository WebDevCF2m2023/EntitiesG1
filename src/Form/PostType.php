<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postTitle')
            ->add('postText')
            ->add('postDateCreated', null, [
                'widget' => 'single_text',
                # si on ne le remplit pas, on envoie la date actuelle
                'empty_data' => date('Y-m-d H:i:s'),
                # non obligation de le remplir
                'required' => false,
            ])

            ->add('postDatePublished', null, [
                'widget' => 'single_text',
            ])
            // en supprimant ce add, on doit modifier AdminPostController pour
            // donner une valeur par défaut à postIsPublished
            //->add('postIsPublished')

            ->add('sections', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'id',
                'multiple' => true,
                # affichage en checkbox
                'expanded' => true,
                # non obligatoire
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
