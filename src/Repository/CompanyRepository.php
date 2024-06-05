<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\CompanyEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method CompanyEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyEntity[]    findAll()
 * @method CompanyEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, CompanyEntity::class);
        $this->manager = $manager;
    }

    public function saveCompany($name, $address, $description, $photo)
    {
        $newCompany = new CompanyEntity();

        $newCompany
            ->setName($name)
            ->setAddress($address)
            ->setDescription($description)
            ->setPhoto($photo);

        $this->manager->persist($newCompany);
        $this->manager->flush();
    }

    public function updateCompany(CompanyEntity $company): CompanyEntity
    {
        $this->manager->persist($company);
        $this->manager->flush();

        return $company;
    }

    public function removeCompany(CompanyEntity $company)
    {
        $this->manager->remove($company);
        $this->manager->flush();
    }
}
