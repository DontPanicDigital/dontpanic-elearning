<?php
namespace DontPanic\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;

/** @noinspection PhpDeprecationInspection */

/**
 * TestOption
 *
 * @ORM\Table(name="test_options")
 * @ORM\Entity
 */
class TestOption
{

    use Identifier;
    use SoftDeletable;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=255, nullable=false)
     */
    private $option;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="correct", type="integer", nullable=false)
     */
    private $correct = 0;

    /**
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="TestQuestion", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="test_questions_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity="UserTestAnswer", mappedBy="option", cascade={"persist"})
     *
     * @var array|ArrayCollection
     **/
    protected $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getOption(): string
    {
        return $this->option ?: '';
    }

    /**
     * @param string $option
     *
     * @return TestOption
     */
    public function setOption(string $option): TestOption
    {
        $this->option = $option ?: null;

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
     * @return TestOption
     */
    public function setDescription(string $description): TestOption
    {
        $this->description = $description ?: null;

        return $this;
    }

    /**
     * Set correct
     *
     * @param int $correct
     *
     * @return User
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
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param TestQuestion $testQuestion
     *
     * @return TestOption
     */
    public function setQuestion(TestQuestion $testQuestion): TestOption
    {
        $this->question = $testQuestion;

        return $this;
    }

    /**
     * Add answer
     *
     * @param UserTestAnswer $userTestAnswer
     *
     * @return TestOption
     */
    public function addAnswer(UserTestAnswer $userTestAnswer): TestOption
    {
        if ($this->answers->contains($userTestAnswer)) {
            return;
        }
        $this->answers->add($userTestAnswer);

        return $this;
    }

    /**
     * Remove answer
     *
     * @param UserTestAnswer $userTestAnswer
     */
    public function removeAnswer(UserTestAnswer $userTestAnswer)
    {
        $this->answers->removeElement($userTestAnswer);
    }

    /**
     * @return array|ArrayCollection|Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}

