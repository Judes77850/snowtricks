<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tricks;
use App\Form\MediaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class)
			->add('description', TextareaType::class)
			->add('slug')
			->add('categoryId', EntityType::class, [
				'label' => 'Catégorie du trick',
				'class' => Categories::class,
				'placeholder' => 'Choisissez une catégorie',
				'choice_label' => 'name'
			])
			->add('authorId')
			->add('media', CollectionType::class, [
				'entry_type' => MediaType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Tricks::class,
		]);
	}
}
