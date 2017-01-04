<?php

namespace DontPanic\Setting;

use DontPanic\Entities\Setting;
use Kdyby\Doctrine\EntityManager;
use DontPanic\Model\DoctrineModel;

class SettingModel extends DoctrineModel
{

    const MAX_ALLOWED_USERS   = 'MAX_ALLOWED_USERS';

    /** @var array */
    private $cache = [ ];

    /**
     * SettingModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Setting::class);
    }

    public function getValue($keyName)
    {
        if (array_key_exists($keyName, $this->cache)) {
            return $this->cache[$keyName];
        }

        /** @var Setting $settingEntity */
        $settingEntity = $this->findOneBy([ 'keyName' => $keyName ]);
        if ($settingEntity) {
            $this->cache[$keyName] = $settingEntity->getValue();

            return $settingEntity->getValue();
        } else {
            throw new SettingValueNotFoundException('Can not find setting value ' . $keyName);
        }

        return null;
    }
}
