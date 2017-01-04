<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * Acl
 *
 * @ORM\Table(name="acl")
 * @ORM\Entity
 */
class Acl
{

    use MagicAccessors;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AclRole", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    /**
     * @ORM\ManyToOne(targetEntity="AclPrivilege", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="privilege_id", referencedColumnName="id")
     */
    protected $privilege;

    /**
     * @ORM\ManyToOne(targetEntity="AclResource", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     */
    protected $resource;

    /**
     * @var integer
     *
     * @ORM\Column(name="access", type="integer", nullable=false)
     */
    private $access = '0';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Acl
     */
    public function setId(int $id): Acl
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     *
     * @return Acl
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param null $privilege
     *
     * @return $this
     */
    public function setPrivilege($privilege = null)
    {
        $this->privilege = $privilege;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param null $resource
     *
     * @return $this
     */
    public function setResource($resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccess(): int
    {
        return $this->access;
    }

    /**
     * @param int $access
     *
     * @return Acl
     */
    public function setAccess(int $access): Acl
    {
        $this->access = $access;

        return $this;
    }
}

