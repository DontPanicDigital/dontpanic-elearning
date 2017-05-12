<?php

namespace DontPanic\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Nette\Utils\Random;

/**
 * Company
 *
 * @ORM\Table(name="companies")
 * @ORM\Entity
 */
class Company
{

    use Identifier;
    use Timestampable;
    use SoftDeletable;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=30, nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="class_name", type="string", length=30, nullable=false)
     */
    private $className;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="companies")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Test", mappedBy="company", cascade={"persist"})
     *
     * @var array|ArrayCollection
     **/
    private $tests;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->tests = new ArrayCollection();
    }

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
     * @return Company
     */
    public function setName(string $name): Company
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return Company
     */
    public function setClassName(string $className): Company
    {
        $this->className = $className;

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
     * @return Company
     */
    public function setToken(): Company
    {
        $this->token = Random::generate(30, 'a-zA-Z0-9');

        return $this;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Company
     */
    public function addUser(User $user)
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addCompany($this);

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
        $user->removeCompany($this);
    }

    /**
     * Get users
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function clearUsers()
    {
        if (count($this->users)) {
            /** @var User $user */
            foreach ($this->users as $user) {
                if ($user instanceof User) {
                    $this->removeUser($user);
                }
            }
        }
    }

    /**
     * Add test
     *
     * @param Test $test
     *
     * @return Company
     */
    public function addTest(Test $test)
    {
        if ($this->testscontains($test)) {
            return;
        }

        $this->tests->add($test);

        return $this;
    }

    /**
     * Remove test
     *
     * @param Test $test
     */
    public function removeTest(Test $test)
    {
        if (!$this->tests->contains($test)) {
            return;
        }

        $this->tests->removeElement($test);
    }

    /**
     * Get tests
     *
     * @return Collection
     */
    public function getTests()
    {
        return $this->tests;
    }

    public function clearTests()
    {
        if (count($this->tests)) {
            /** @var Test $test */
            foreach ($this->tests as $test) {
                if ($test instanceof Test) {
                    $this->removeTest($test);
                }
            }
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user);
    }
}