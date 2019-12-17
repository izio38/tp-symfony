<?php


namespace App\Service;


use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ShopService
{
    private $productRepository;
    private $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function findProductById(int $id) {
        return $this->productRepository->findOneBy(["id" => $id]);
    }

    public function findProductsByCategoryId(int $id) {
        $category = $this->categoryRepository->findOneBy(["id" => $id]);

        return $category->getProducts();
    }

    public function getAllCategories() {
        return $this->categoryRepository->findAll();
    }

    public function findByLikeOrDescriptionLabel(string $searchLabel) {
        return $this->productRepository->findByLikeOrDescriptionLabel($searchLabel);
    }
}