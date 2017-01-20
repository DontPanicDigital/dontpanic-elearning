<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * UserTestAnswer
 *
 * @ORM\Table(name="user_test_answers")
 * @ORM\Entity
 */
class UserTestAnswer
{

    use Identifier;
    use Timestampable;

    /**
     * @var UserTestAnswer
     *
     * @ORM\ManyToOne(targetEntity="TestOption", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="test_options_id", referencedColumnName="id")
     */
    private $option;

    /**
     * @var UserTestAnswer
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="correct", type="integer", nullable=false)
     */
    private $correct = 0;

    /**
     * @return TestOption
     */
    public function getOption(): TestOption
    {
        return $this->option;
    }

    /**
     * @param TestOption $testOption
     *
     * @return UserTestAnswer
     */
    public function setOption(TestOption $testOption): UserTestAnswer
    {
        $this->option = $testOption;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserTestAnswer
     */
    public function setUser(User $user): UserTestAnswer
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set correct
     *
     * @param int $correct
     *
     * @return UserTestAnswer
     */
    public function setCorrect(int $correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return int
     */
    public function getCorrect(): int
    {
        return $this->correct;
    }

    /**
     * Set correct
     *
     * @param int $sort
     *
     * @return UserTestAnswer
     */
    public function setSort(int $sort)
    {
        $this->correct = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return int
     */
    public function getSort(): int
    {
        return $this->correct;
    }
}

