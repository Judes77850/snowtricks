<?php

namespace App\Service;

use App\Entity\Tricks;
use App\Entity\UserProfilePicture;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
	public function __construct(private readonly string $targetDirectory, private readonly SluggerInterface $slugger)
	{

	}

	public function upload(UploadedFile $file): string
	{
		$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFilename = $this->slugger->slug($originalFilename);
		$fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

		try {
			$file->move($this->getTargetDirectory(), $fileName);
		} catch (FileException $e) {
			throw new FileException($e);
		}

		return $fileName;
	}

	public function uploadImages(Tricks $trick): void
	{
		$mainImage = null;

		foreach ($trick->getImages() as $image) {
			if ($image->getFile() !== null) {
				$image->setPath($this->upload($image->getFile()));

				if ($image->getIsMain()) {
					$mainImage = $image;
				}
			} elseif ($image->getPath() === null && $image->getFile() === null) {
				$trick->removeImage($image);
			}
		}

		if ($mainImage !== null) {
			$trick->setMainImage($mainImage);
		}
	}

	public function uploadProfilePicture(UploadedFile $file): string
	{
		$fileName = md5(uniqid()) . '.' . $file->guessExtension();
		$file->move($this->getTargetDirectory(), $fileName);

		return $fileName;
	}

	public function getTargetDirectory(): string
	{
		return $this->targetDirectory;
	}

	public function uploadVideos(Tricks $trick): void
	{

		foreach ($trick->getVideos() as $video) {
			$check = parse_url($video->getUrl(), PHP_URL_HOST);
			parse_str(parse_url($video->getUrl(), PHP_URL_QUERY), $videoId);


			if ($check === "www.youtube.com" && array_key_exists('v', $videoId)) {
				$video->setVideoId($videoId['v']);

				$trick->addVideo($video);
			} elseif ($check === "www.dailymotion.com") {
				$parsedUrl = parse_url($video->getUrl());
				$pathSegments = explode('/', trim($parsedUrl['path'], '/'));
				$dailymotionId = end($pathSegments);
				$video->setVideoId($dailymotionId);
				$trick->addVideo($video);
			} else {
				$trick->removeVideo($video);
			}
		}
	}
}



