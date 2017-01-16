<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\MagicAccessors;
use Doctrine\Common\Collections\Collection;

/**
 * AclResource
 *
 * @ORM\Table(name="acl_resources")
 * @ORM\Entity
 */
class AclResource
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
     * @ORM\OneToMany(targetEntity="AclResource", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="AclResource", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

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

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return AclResource
     */
    public function setId(int $id): AclResource
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     *
     * @return AclResource
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

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
     * @return AclResource
     */
    public function setKeyName(string $keyName): AclResource
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
     * @return AclResource
     */
    public function setName(string $name): AclResource
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    /**
     * @param string $comment
     *
     * @return AclResource
     */
    public function setComment(string $comment): AclResource
    {
        $this->comment = $comment ?: null;

        return $this;
    }

    /**
     * @param AclResource $aclResource
     *
     * @return $this
     */
    public function addChildren(AclResource $aclResource)
    {
        $this->children[] = $aclResource;

        return $this;
    }

    /**
     * @param AclResource $aclResource
     */
    public function removeChildren(AclResource $aclResource)
    {
        $this->children->removeElement($aclResource);
    }

    /**
     * Get category
     *
     * @return Collection
     */
    public function getChildrens()
    {
        return $this->children;
    }
}