<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('temp_celsius', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter temperature',
                ],
            ])
            ->add('feels_like_celsius', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter feels like temperature',
                ],
            ])
            ->add('weather_condition', ChoiceType::class, [
                'choices' => [
                    'Clear' => 'Clear',
                    'Few Clouds' => 'Few Clouds',
                    'Cloudy' => 'Cloudy',
                    'Rain' => 'Rain',
                    'Heavy Rain' => 'Heavy Rain',
                    'Thunderstorm' => 'Thunderstorm',
                    'Snow' => 'Snow',
                    'Fog' => 'Fog',
                    'Windy' => 'Windy',
                ],
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city_name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
