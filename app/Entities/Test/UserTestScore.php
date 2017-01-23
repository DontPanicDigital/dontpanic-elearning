<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * UserTestScore
 *
 * @ORM\Table(name="user_test_score")
 * @ORM\Entity
 */
class UserTestScore extends BaseEntity
{

    use Identifier;
    use Timestampable;
    /**
     * @var integer
     *
     * @ORM\Column(name="correct_answers", type="integer", nullable=false)
     */
    private $correctAnswers = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="wrong_answers", type="integer", nullable=false)
     */
    private $wrongAnswers = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="done", type="integer", nullable=false)
     */
    private $done = 0;

    /**
     * @var UserTestScore
     *
     * @ORM\ManyToOne(targetEntity="Test", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="tests_id", referencedColumnName="id")
     */
    private $test;

    /**
     * @var UserTestScore
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return int
     */
    public function getCorrectAnswers(): int
    {
        return $this->correctAnswers;
    }

    /**
     * @param int $correctAnswers
     */
    public function setCorrectAnswers(int $correctAnswers)
    {
        $this->correctAnswers = $correctAnswers;
    }

    /**
     * @return int
     */
    public function getWrongAnswers(): int
    {
        return $this->wrongAnswers;
    }

    /**
     * @param int $wrongAnswers
     */
    public function setWrongAnswers(int $wrongAnswers)
    {
        $this->wrongAnswers = $wrongAnswers;
    }

    /**
     * @return int
     */
    public function isDone(): int
    {
        return $this->done;
    }

    /**
     * @param int $done
     *
     * @return UserTestScore
     */
    public function setDone(int $done): UserTestScore
    {
        $this->done = $done;

        return $this;
    }

    /**
     * @return Test
     */
    public function getTest(): Test
    {
        return $this->test;
    }

    /**
     * @param Test $test
     *
     * @return UserTestScore
     */
    public function setTest(Test $test): UserTestScore
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return UserTestScore
     */
    public function getUser(): UserTestScore
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserTestScore
     */
    public function setUser(User $user): UserTestScore
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullWell()
    {
        return $this->correctAnswers && !$this->wrongAnswers;
    }
}

