<?php

/*
 * Copyright (C) 2019 sasha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Form;

use DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Player;
use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of PlayerType
 *
 * @author sasha
 */
class PlayerType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ton pseudo',
                'attr' => ['placeholder' => 'Ex : Rotaldo']
            ])
            ->add('game', EntityType::class, [
                'label' => 'Pour quel match ?',
                'class' => Game::class,
                'query_builder' => function (GameRepository $er) {
                    $now = new DateTime();
                
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.date > :date')
                        ->setParameter('date', $now)
                        ->orderBy('m.date', 'ASC');
                },
                'choice_label' => 'getDateString',
                'mapped' => false
            ])
            ->add('S\'inscrire', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
