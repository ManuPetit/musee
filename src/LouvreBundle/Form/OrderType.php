<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 11:29
 */

namespace LouvreBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venueDate', DateType::class, [
                'label' => 'Date de visite :',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'venuedatepick']
            ])
            ->add('customerEmail', EmailType::class, [
                'label' => 'Votre adresse email :'
            ])
            ->add('duration', EntityType::class, [
                'class' => 'LouvreBundle\Entity\Duration',
                'choice_label' => 'name',
                'label' => 'Type de ticket :'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Je commande'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'LouvreBundle\Entity\Order'
        ]);
    }
}