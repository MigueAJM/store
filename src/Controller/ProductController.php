<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/product', name: 'app_product')]
class ProductController extends Controller
{
    private mixed $error;
    #[Route('/all', name: '_all', methods: ['GET'])]
    public function all(ProductRepository $productRepository): Response
    {
        $productsArray = [];
        foreach ($productRepository->findAll() as $p) {
            $productsArray[] = $p->toArray();
        }
        return $this->json($productsArray);
    }

    #[Route('/query', name: '_query', methods: ['GET'])]
    public function query(Request $request, ProductRepository $productRepository): Response
    {
        $allowProperties = Product::getProperties();
        $criteria = [];
        foreach ($allowProperties as $k) {
            $value = $request->query->get($k);
            if($value) $criteria[$k] = $value;
        }
        $products = [];
        foreach($productRepository->findBy($criteria) as $p){
            $products[] = $p->toArray();
        }
        $code = count($products) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        return $this->json($products, $code);
    }

    #[Route('/save', name: '_name', methods: ['POST', 'PUT'])]
    public function save(Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface): Response
    {
        $userActive = $this->getAuthenticatedUser($request, $entityManagerInterface);
        if(!$userActive->getRole() || $userActive->getRole()->getId() != $_ENV['ROLE_ADMIN_ID']) {
            $code = Response::HTTP_UNAUTHORIZED;
            $data = ['error' => Response::$statusTexts[$code]];
            return $this->json($data, $code);
        }
        $data = [];
        $code = Response::HTTP_CREATED;
        $data = json_decode($request->getContent(), true);
        $categoryRepository = $entityManagerInterface->getRepository(Category::class);
        $category = $categoryRepository->find($data['categoryId']);
        if(!$category){
            return $this->json(['error' => "Not found category."], Response::HTTP_BAD_REQUEST);
            return false;
        }
        $data['category'] = $category;
        if($request->getMethod() === Request::METHOD_PUT){
            $code = Response::HTTP_OK;
            if(!$this->update($entityManagerInterface, $validatorInterface, $data)){
                $data = ['error' => $this->error];
                $code = Response::HTTP_BAD_REQUEST;
            }
            return $this->json($data, $code);
        }
        if(!$this->create($entityManagerInterface, $validatorInterface, $data)){
            $data = ['error' => $this->error];
            $code = Response::HTTP_BAD_REQUEST;
        }
        return $this->json($data, $code);
    }

    private function create(EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface, array $data): bool
    {
        $product = Product::fromArray($data);
        $errors = $validatorInterface->validate($product);
        if(count($errors)){
            $this->setError($errors);
            return false;
        }
        $entityManagerInterface->persist($product);
        $entityManagerInterface->flush();
        return true;
    }

    private function update(EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface, array $data): bool
    {
        $productRepository = $entityManagerInterface->getRepository(Product::class);
        $product = $productRepository->find($data['id']);
        if(!$product){
            $this->setError("Not found product.");
            return false;
        }
        unset($data['id']);
        foreach ($data as $k => $v) {
            $method = "set".ucfirst($k);
			if(method_exists($product::class, $method)){
				$product->$method($v);
			}
        }
        $errors = $validatorInterface->validate($product);
        if(count($errors)){
            $this->setError($errors);
            return false;
        }
        $entityManagerInterface->flush();
        return true;
    }

    private function setError(mixed $error): static
    {
        $this->error = $error;
        return $this;
    }
}
