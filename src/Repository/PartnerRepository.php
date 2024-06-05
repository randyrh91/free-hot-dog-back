<?php

namespace App\Repository;

use App\Entity\PartnerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PartnerEntity>
 *
 * @method PartnerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartnerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartnerEntity[]    findAll()
 * @method PartnerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnerRepository extends ServiceEntityRepository
{

    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, PartnerEntity::class);
        $this->manager = $manager;
    }

    public function savePartner($newPartner, $name, $email, $password, $phone, $activationCode)
    {
        
        $newPartner
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
            ->setPhone($phone)
            ->setActivationCode($activationCode);

        $this->manager->persist($newPartner);
        $this->manager->flush();
    }

    public function updatePartner(PartnerEntity $partener): PartnerEntity
    {
        $this->manager->persist($partener);
        $this->manager->flush();

        return $partener;
    }


    public function removePartner(PartnerEntity $partener)
    {
        $this->manager->remove($partener);
        $this->manager->flush();
    }
}
