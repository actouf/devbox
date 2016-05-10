<?php

/**
 * Form type
 *
 * @author Adrien Jerphagnon <adrien.j@disko.fr>
 */

namespace Disko\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 *  Form Type
 */
class UserType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('civility', ChoiceType::class, array(
            'choices'   => array(
                'Monsieur' => 'm',
                'Madame' => 'mme',
                'Mademoiselle' => 'mlle'
            ),
            'choices_as_values' => true,
            'placeholder' => false,
            'required'  => false,
        ));
        $builder->add('firstName', TextType::class);
        $builder->add('lastName',  TextType::class);
        $builder->add('email',     EmailType::class);
        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array('required' => false),
            'first_options'   =>  array('label' => 'Mot de passe'),
            'second_options'  =>  array('label' => 'Confirmer mot de passe'),
            'invalid_message' =>  'Les mots de passe ne sont pas identique'
        ));

        $builder->add('birthday', DateType::class, array(
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/y',
        ));

        $builder->add('localisations', CollectionType::class, array(
            'entry_type' => LocalisationType::class,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true
        ));

        $builder->add('locked', CheckboxType::class, array('required' => false));
        $builder->add('news', CheckboxType::class, array('required' => false));
        $builder->add('enabled', CheckboxType::class, array('required' => false));

        $builder->add('fileAvatar',          FileType::class);
        $builder->add('fileAtDeleteAvatar',  CheckboxType::class, array(
            'required' => false
        ));
    }

    /**
     * Configure Options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Disko\UserBundle\Entity\User',
            'validation_groups' => array('Default', 'Admin'),
            'csrf_token_id'  => 'Admin',
        ));
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'user';
    }
}
