<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/customers", name="customer_list")
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page');
        $data = [];
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/customers/{customerId}", name="customer")
     */
    public function view(int $customerId): JsonResponse
    {
        $data = [$customerId];
        return new JsonResponse($data);
    }
}