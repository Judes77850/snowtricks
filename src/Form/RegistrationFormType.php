<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
				'required'=>true,
	            'label'=>'Votre adresse email',
	            'attr'=>[
					'placeholder'=>'votremail@email.com'
	            ],
            ])
            ->add('password', RepeatedType::class, [
				'type'=>PasswordType::class,
	            'first_options'=>[
					'label'=>'Votre mot de passe',
	            ],
	            'second_options'=>[
		            'label'=>'Confirmez votre mot de passe',
	            ],
	            'constraints' => [
		            new NotBlank([
			            'message' => 'Veuillez entrer un mot de passe',
		            ]),
		            new Length([
			            'min' => 8,
			            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
			            'max' => 4096,
		            ]),
		            new Regex([
			            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/',
			            'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
		            ]),
		            new NotCompromisedPassword([
			            'message' => 'Ce mot de passe a été divulgué dans une fuite de données, veuillez en choisir un autre.',
		            ]),
	            ],
            ])
            ->add('userName', TextType::class, [
	            'label'=>'Votre nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
