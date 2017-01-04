<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\BaseEntity;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * AclPrivilege
 *
 * @ORM\Table(name="acl_privileges")
 * @ORM\Entity
 */
class AclPrivilege
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
     * @var string
     *
     * @ORM\Column(name="key_name", type="string", length=64, nullable=false)
     */
    private $keyName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="AclResource", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="acl_resources_id", referencedColumnName="id")
     */
    protected $resource;

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
     * @return AclPrivilege
     */
    public function setId(int $id): AclPrivilege
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * @param string $keyName
     *
     * @return AclPrivilege
     */
    public function setKeyName(string $keyName): AclPrivilege
    {
        $this->keyName = $keyName;

        return $this;
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
     * @return AclPrivilege
     */
    public function setName(string $name): AclPrivilege
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return AclPrivilege
     */
    public function setComment(string $comment): AclPrivilege
    {
        $this->comment = $comment ?: null;

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
     * @return AclPrivilege
     */
    public function setResource($resource = null): AclPrivilege
    {
        $this->resource = $resource;

        return $this;
    }
}

