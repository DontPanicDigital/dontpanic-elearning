<?php

namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="tokens")
 */
class Token extends BaseEntity
{
    use Identifier;
    use Timestampable;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens", cascade={"persist"})
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", nullable=TRUE, onDelete="CASCADE")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     *
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     *
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Token
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getTokenString()
    {
        return $this->token;
    }

    public function setTokenString($token)
    {
        $this->token = $token;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     */
    public function setExpiresAt(\DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expiresAt && $this->expiresAt < new \DateTime();
    }
}