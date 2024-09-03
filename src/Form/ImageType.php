<?php

// src/Form/ImageType.php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;

class ImageType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('file', FileType::class, [
				'label' => 'Image',
				'mapped' => 'true',
				'required' => false,
				'constraints' => [
					new NotNull(message: 'veuillez renseigner ce champ', groups: ['new'])
				]
			])
			->add('isMain', CheckboxType::class, [
				'label' => 'Image principale:',
				'required' => false,
				'mapped' => true,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Image::class,
			'validation_groups' => [],
		]);
	}
}

