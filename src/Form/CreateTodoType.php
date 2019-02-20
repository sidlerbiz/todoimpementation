<?php

/*
 * This file is part of the TodoList package.
 * (c) Aleksey Mihayluk <sidlerbiz@gmail.com>
 */

namespace App\Form;

use Neo\Bundle\TodoBundle\FormType\AbstractCreateTodoType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTodoType extends AbstractCreateTodoType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Todo',
        ]);
    }
}
