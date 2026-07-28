<?php
declare(strict_types=1);

namespace Assets\Db\File;

use Common\Db\Entity as DbEntity;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: 'assets_files')]
#[ORM\Entity(repositoryClass: Repository::class)]
class Entity implements DbEntity
{
	#[ORM\Id]
	#[ORM\Column(type: 'uuid')]
	private UuidInterface $id;

	#[ORM\Column(type: 'string', length: 500)]
	private string $fileName;

	#[ORM\Column(type: 'string', length: 50)]
	private string $size;

	#[ORM\Column(type: 'string', length: 100)]
	private string $mimeType;

	#[ORM\Column(type: 'datetime')]
	private DateTimeInterface $createdDate;

	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->id          = Uuid::uuid4();
		$this->createdDate = new DateTime();
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function setId(UuidInterface $id): void
	{
		$this->id = $id;
	}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function setFileName(string $fileName): void
	{
		$this->fileName = $fileName;
	}

	public function getSize(): string
	{
		return $this->size;
	}

	public function setSize(string $size): void
	{
		$this->size = $size;
	}

	public function getMimeType(): string
	{
		return $this->mimeType;
	}

	public function setMimeType(string $mimeType): void
	{
		$this->mimeType = $mimeType;
	}

	public function getCreatedDate(): DateTimeInterface
	{
		return $this->createdDate;
	}

	public function setCreatedDate(DateTimeInterface $createdDate): void
	{
		$this->createdDate = $createdDate;
	}
}