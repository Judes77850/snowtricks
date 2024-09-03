<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class TrickType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class)
			->add('description', TextareaType::class)
			->add('categoryId', EntityType::class, [
				'label' => 'Catégorie du trick',
				'class' => Categories::class,
				'placeholder' => 'Choisissez une catégorie',
				'choice_label' => 'name'
			])

			->add('images', CollectionType::class, [
				'entry_type' => ImageType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'label' => false,
				'constraints' => [
					new Count(min: 1, minMessage: 'Merci d\'ajouter au moins une image', groups: ['new', 'edit'])
				],
				'mapped' => true,
			])
			->add('videos', CollectionType::class, [
				'entry_type' => VideoType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'label' => false,
				'constraints' => [
					new Count(min: 1, minMessage: 'Merci d\'ajouter au moins une video', groups: ['new', 'edit'])
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Tricks::class,
			'csrf_protection' => true,
			'csrf_field_name' => '_token',
			'csrf_token_id' => 'trick_item',
			'validation_groups' => []
		]);
	}
}
