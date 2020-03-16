<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

 
    public function findByName($name): array
    {
        $users = $this->createQueryBuilder('u')
            ->andWhere('u.name = :name')
            ->setParameter('name', $name)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();

            
            $usersArray = [];
    
            foreach ($users as $user) {
                $usersArray[] = $this->transform($user);
            }
    
            return $usersArray;

    }

    public function findById($id)
    {
        $user = $this->find($id);

        if (!$user) {
            return ['status'=> "'No ID found'"];
        }

        return $user;
    }

    public function removeUser($id)
    {
        $user = $this->find($id);

        if (!$user) {
            return ['status'=> "'No ID found'"];
            
        }

  
        $query = $this->createQueryBuilder('u')
        ->andWhere('u.id = :id')
        ->delete()
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();


        return ['status'=> "User delete"];
    }



    public function transform(User $user)
    {
        return [
                'id'    => (int) $user->getId(),
                'name' => (string) $user->getName(),
                'lastName' => (string) $user->getLastName(),
                'email' => (string) $user->getEmail(),
                'ci' => (string) $user->getCi(),
        ];
    }

    public function transformAll()
    {
        $users = $this->findAll();
        $usersArray = [];

        foreach ($users as $user) {
            $usersArray[] = $this->transform($user);
        }

        return $usersArray;
    }
}
