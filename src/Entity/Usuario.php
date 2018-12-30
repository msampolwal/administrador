<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Rol", inversedBy="usuarios")
     */
    private $perfiles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->perfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $opciones = [ 'cost' => 12, ]; 
        $password = password_hash($password, PASSWORD_BCRYPT, $opciones); 
        $this->password = $password; 
        return $this;
    }

    /**
     * @return Collection|Rol[]
     */
    public function getPerfiles(): Collection
    {
        return $this->perfiles;
    }

    public function addPerfil(Rol $perfil): self
    {
        if (!$this->perfiles->contains($perfil)) {
            $this->perfiles[] = $perfil;
        }

        return $this;
    }

    public function removePerfil(Rol $perfil): self
    {
        if ($this->perfiles->contains($perfil)) {
            $this->perfiles->removeElement($perfil);
        }

        return $this;
    }
    public function eraseCredentials()
    {}

    public function getSalt()
    {}

    public function getUsername()
    {
        return $this->email;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
    public function getRoles()
    {
        $resultado = array();
        foreach ($this->getPerfiles() as $perfil){
            $resultado[] = 'ROLE_'.$perfil->getCodigo();
        }
        return array_unique($resultado);
    }

}
