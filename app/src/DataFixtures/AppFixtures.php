<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $appleCompany = (new Company())->setSymbol('AAPL')->setName('Apple Inc.');
        $googleCompany = (new Company())->setSymbol('GOOGL')->setName('Alphabet Inc.');

        $manager->persist($appleCompany);
        $manager->persist($googleCompany);
        $manager->flush();
    }
}
