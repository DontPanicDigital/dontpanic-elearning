<?php
namespace DontPanic\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Nette\Utils\Random;

/**
 * SmsCode
 *
 * @ORM\Table(name="sms_codes")
 * @ORM\Entity
 */
class SmsCode
{

    use Identifier;
    use Timestampable;
    use SoftDeletable;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=30, nullable=false)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Test", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="tests_id", referencedColumnName="id")
     */
    private $test;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return SmsCode
     */
    public function setCode(string $code): SmsCode
    {
        $this->code = $code;

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
     * @return SmsCode
     */
    public function setToken(): SmsCode
    {
        $this->token = Random::generate(30, 'a-zA-Z0-9');

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return SmsCode
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     *
     * @return SmsCode
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }
}