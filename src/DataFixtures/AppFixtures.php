<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setDescription("Une gamme de livre pas comme les autres.");
        $category->setLabel("Javascript");
        $category->setImageUri("https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/JavaScript-logo.png/600px-JavaScript-logo.png");

        $product = new Product();
        $product->setImageUri("https://fr.eloquentjavascript.net/img/ejs.png");
        $product->setLabel("Eloquent Javascript");
        $product->setDescription("Un super livre plein de surprise.");
        $product->setPrice(11.90);
        $product->setCategory($category);

        $manager->persist($category);
        $manager->persist($product);

        $manager->flush();
    }
}
