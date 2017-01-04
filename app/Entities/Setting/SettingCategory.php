<?php
namespace DontPanic\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * SettingCategory
 *
 * @ORM\Table(name="setting_categories")
 * @ORM\Entity
 */
class SettingCategory extends BaseEntity
{

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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Setting", mappedBy="category", cascade={"persist"})
     *
     * @var array|\Doctrine\Common\Collections\ArrayCollection
     **/
    protected $settings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SettingCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Setting $setting
     *
     * @return $this
     */
    public function addSetting(Setting $setting)
    {
        $this->settings[] = $setting;

        return $this;
    }

    /**
     * @param Setting $setting
     */
    public function removeSetting(Setting $setting)
    {
        $this->settings->removeElement($setting);
    }

    /**
     * @return array|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getSettings()
    {
        return $this->settings;
    }
}

