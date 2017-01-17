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
}

