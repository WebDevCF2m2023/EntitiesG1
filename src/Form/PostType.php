<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Section;
use App\Entity\Tag;
use App\Entity\User;
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
                # non obligatoire
                'required' => false,
                # si vide on envoie la date du jour
                'empty_data' => date('Y-m-d H:i:s'),
            ])
            ->add('postDatePublished', null, [
                'widget' => 'single_text',
            ])
            ->add('postIsPublished')
            ->add('sections', EntityType::class, [
                'class' => Section::class,
                # choix du titre dans les checkboxes
                'choice_label' => 'sectionTitle',
                'multiple' => true,
                'expanded' => true,
            ])
            # on peut se passer des tags pour le moment
            /*->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
            ])*/
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
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
