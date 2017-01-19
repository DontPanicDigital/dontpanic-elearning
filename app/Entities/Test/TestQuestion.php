<?php
namespace DontPanic\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Nette\Utils\Random;

/**
 * TestQuestion
 *
 * @ORM\Table(name="test_questions")
 * @ORM\Entity
 */
class TestQuestion
{

    use Identifier;
    use Timestampable;
    use SoftDeletable;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=30, nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @var Test
     *
     * @ORM\ManyToOne(targetEntity="Test", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="tests_id", referencedColumnName="id")
     */
    private $test;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TestOption", mappedBy="question", cascade={"persist"})
     **/
    private $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return TestQuestion
     */
    public function setToken(): TestQuestion
    {
        $this->token = Random::generate(30, '0-9A-Za-z');

        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question ?: '';
    }

    /**
     * @param string $question
     *
     * @return TestQuestion
     */
    public function setQuestion(string $question): TestQuestion
    {
        $this->question = $question ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?: '';
    }

    /**
     * @param string $description
     *
     * @return TestQuestion
     */
    public function setDescription(string $description): TestQuestion
    {
        $this->description = $description ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return TestQuestion
     */
    public function setType(string $type): TestQuestion
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return TestQuestion
     */
    public function setSort($sort): TestQuestion
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param Test $test
     *
     * @return TestQuestion
     */
    public function setTest(Test $test): TestQuestion
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Add vote
     *
     * @param TestOption $testOption
     *
     * @return TestQuestion
     */
    public function addOptions(TestOption $testOption): TestQuestion
    {
        if ($this->options->contains($testOption)) {
            return;
        }
        $this->options->add($testOption);
        $testOption->setQuestion($this);

        return $this;
    }

    /**
     * @param TestOption $testOption
     */
    public function removeOption(TestOption $testOption)
    {
        if (!$this->options->contains($testOption)) {
            return;
        }
        $this->options->removeElement($testOption);
    }
}

