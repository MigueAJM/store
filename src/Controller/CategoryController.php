<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/category', name: 'category')]
class CategoryController extends Controller
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new category controller!',
        ]);
    }

    #[Route('/save', name: '_save', methods: ['POST', 'PUT'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userActive = $this->getAuthenticatedUser($request, $entityManager);
        if(!$userActive->getRole() || $userActive->getRole()->getId() != $_ENV['ROLE_ADMIN_ID']) {
            $code = Response::HTTP_UNAUTHORIZED;
            $data = ['error' => Response::$statusTexts[$code]];
            return $this->json($data, $code);
        }
        $data = [];
        $code = Response::HTTP_CREATED;
        $data = json_decode($request->getContent(), true);
        $res = null;
        if($request->getMethod() === Request::METHOD_POST){
            $res = $this->create($entityManager, $data);
        }
        return $this->json([]);
    }

    private function create()
    {

    }

    private function update()
    {

    }
}
