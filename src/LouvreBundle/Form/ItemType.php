<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 17/08/2017
 * Time: 17:11
 */

namespace LouvreBundle\Form;

use Doctrine\ORM\EntityRepository;
use LouvreBundle\Entity\Country;
use LouvreBundle\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom :'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance :',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'birthdatepick']
            ])
            ->add('country', EntityType::class, [
                'class' => 'LouvreBundle\Entity\Country',
                'query_builder' => function(CountryRepository $repository){
                    return $repository->getByAlphabeticalOrder();
                },
                'choice_label' => 'name',
                'expanded' => false,
                'label' => 'Pays :',
                'preferred_choices' => function($country, $key, $index) {
                    return $country->getId() ==  20 || $country->getId() ==  43
                        || $country->getId() ==  57 || $country->getId() ==  68
                        || $country->getId() ==  75 || $country->getId() ==  77
                        || $country->getId() ==  110 || $country->getId() ==  134;
                }
            ])
            ->add('isReduceRate', CheckboxType::class, [
                'label' => 'Tarif réduit',
                'required' => false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'LouvreBundle\Entity\Item'
        ]);
    }

}