<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Category
{
    private UuidInterface $id;

    private $name;

    private $books;

    public function __construct(UuidInterface $id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->books = new ArrayCollection();
    }

    public static function create(string $name): self
    {
        return new self(Uuid::uuid4(), $name);
    }


    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeCategory($this);
        }

        return $this;
    }

    public function update(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
