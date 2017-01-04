<?php

namespace DontPanic\Acl;

use DontPanic\Entities\AclResource;
use DontPanic\Model\DoctrineModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Strings;

class AclResourceModel extends DoctrineModel
{

    /**
     * AclResourceModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(AclResource::class);
    }

    public function create($name)
    {
        try {
            $resourceEntity = new AclResource();
            $resourceEntity->setName($name);
            $resourceEntity->setKeyName(Strings::webalize($name));
            $this->save($resourceEntity);

            return $resourceEntity;
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateAclResourceException;
        }
    }

    public function rootList()
    {
        return $this->createQueryBuilder('r')->andWhere('r.parent IS NULL')->getQuery()->getResult();
    }

    public function getParentResource($parentId, $parentKey, &$resources)
    {
        $qb = $this->createQueryBuilder('r');

        null === $parentId
            ? $qb->andWhere('r.parent IS NULL')
            : $qb->andWhere('r.parent = :parentId')
                 ->setParameter('parentId', $parentId);

        $qb = $qb->getQuery()->getResult();

        /** @var AclResource $resource */
        foreach ($qb as $resource) {
            $resources[] = [ 'key_name' => $resource->getKeyName(), 'parent_key' => $parentKey ];
            $this->getParentResource($resource->getId(), $resource->getKeyName(), $resources);
        }
    }

    public function getResources()
    {
        $resources = [];
        $this->getParentResource(null, null, $resources);

        return $resources;
    }
}
