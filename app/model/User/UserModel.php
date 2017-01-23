<?php

namespace DontPanic\User;

use DontPanic\Entities\User;
use DontPanic\Exception\Code\UserCodes;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Nette\Database\UniqueConstraintViolationException;

class UserModel extends DoctrineModel
{

    /**
     * UserModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(User::class);
    }

    public function getUserList()
    {
        return $this->createQueryBuilder('u')
                    ->andWhere('u.deletedAt IS NULL');
    }

    /**
     * @param      $id
     * @param bool $deleted
     *
     * @return mixed
     */
    public function findById($id, $deleted = false)
    {
        return $this->find($id, $deleted);
    }

    /**
     * @param string $email
     * @param bool   $deleted
     *
     * @return User
     */
    public function findByEmail($email, $deleted = false)
    {
        return $this->findOneBy([ 'email' => $email ], [], $deleted);
    }

    /**
     * @param string $phone
     * @param bool   $deleted
     *
     * @return User
     */
    public function findByPhone($phone, $deleted = false)
    {
        $phone = preg_replace('/\s+/', '', $phone);

        return $this->findOneBy([ 'phone' => $phone ], [], $deleted);
    }

    /**
     * @param User      $user
     * @param bool|true $sync
     */
    public function saveUser(User $user, $sync = true)
    {
        $this->save($user);
    }

    public function hasUserRole(User $user, array $roles)
    {
        if (!$user) {
            return false;
        }
        $corrects  = 0;
        $userRoles = $user->getUserRole(true);

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $corrects++;
            }
        }

        return $corrects === count($roles);
    }

    public function markAsDeleted(User $user = null, $userId = null)
    {
        try {
            if ($userId !== null) {
                $user = $this->find($userId);
            }
            if (!$user) {
                throw new NotFoundException('User', UserCodes::USER_NOT_FOUND);
            }
            $user->setDeletedAt(new \DateTime());
            $this->save($user);

            return $user;
        } catch (NotFoundException $e) {
            throw new DeleteException('User not found', UserCodes::USER_NOT_FOUND);
        } catch (UniqueConstraintViolationException $e) {
            throw new DeleteException();
        }
    }
}
