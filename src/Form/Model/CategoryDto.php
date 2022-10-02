<?php

namespace App\Form\Model;

use App\Entity\Category;
use Ramsey\Uuid\UuidInterface;

class CategoryDto {
    public function __construct(
        public ?UuidInterface $id = null,
        public ?string $name = null
    ) {
    }

    public static function createFromCategory(Category $category): self
    {
        return new self(
            $category->getId(),
            $category->getName()
        );
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}