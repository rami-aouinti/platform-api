<?php

declare(strict_types=1);

namespace App\Quiz\Infrastructure\DataFixtures;

use App\Quiz\Domain\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @package App\Quiz\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class ConfigurationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $configuration1 = new Configuration();
        $configuration1->setConst('MAIN_ALLOW_USER_ACCOUNT_CREATION');
        $configuration1->setDescription('Autoriser la création de nouveaux comptes utilisateurs (register)');
        $configuration1->setValue('1');
        $manager->persist($configuration1);

        $manager->flush();
    }
}
