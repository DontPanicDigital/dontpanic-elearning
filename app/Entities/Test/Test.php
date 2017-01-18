<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Nette\Utils\Random;

/**
 * Test
 *
 * @ORM\Table(name="tests")
 * @ORM\Entity
 */
class Test
{

    use Identifier;
    use Timestampable;
    use SoftDeletable;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=30, nullable=false)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="Company", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="companies_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Test
     */
    public function setName(string $name): Test
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Test
     */
    public function setDescription(string $description): Test
    {
        $this->description = $description ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return Test
     */
    public function setToken(): Test
    {
        $this->token = Random::generate(30, 'a-zA-Z0-9');

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Test
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return Test
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }
}