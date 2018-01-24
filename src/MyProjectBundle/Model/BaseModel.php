<?php

namespace MyProjectBundle\Model;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use MyProjectBundle\Document\Document;
use MyProjectBundle\Entity\Entity;
use ONGR\ElasticsearchBundle\Service\Manager;

/**
 * Class BaseModel
 * @package MyProjectBundle\Model
 */
abstract class BaseModel
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * BaseModel constructor.
     * @param Manager $manager
     * @param EntityManager $entityManager
     * @param Logger $logger
     */
    public function __construct(Manager $manager, EntityManager $entityManager, Logger $logger)
    {
        $this->manager = $manager;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param $className
     * @return \Doctrine\ORM\EntityRepository|null|\ONGR\ElasticsearchBundle\Service\Repository
     */
    protected function getRepository($className)
    {
        $obj = new $className();
        if ($obj instanceof Entity) {
            /** @var Connection $connection */
            $connection = $this->entityManager->getConnection();
            if ($connection->ping() === false) {
                $connection->close();
                $connection->connect();
            }
            return $this->entityManager->getRepository($className);
        }

        if ($obj instanceof Document) {
            return $this->manager->getRepository($className);
        }

        return null;
    }

    protected function save($object)
    {
        if ($object instanceof Entity) {
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        }

        if ($object instanceof Document) {
            $this->manager->persist($object);
            $this->manager->commit();
        }

        return true;
    }
}
