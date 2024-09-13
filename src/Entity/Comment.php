<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        # On souhaite ne pas perdre la moitié
        # des numériques... donc unsigned !
        options: [
            'unsigned' => true,
        ]
    )]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
