<?php

namespace App\Controller;

use App\Interfaces\CustomerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var CustomerServiceInterface
     */
    protected $customerService;
    
    /**
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }
    
    /**
     * @Route("/customers", name="customer_list")
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page') ?? 1;
        $quantity = $request->get('q') ?? 100;
        $data = $this->customerService->list($page, $quantity);
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/customers/{customerId}", name="customer")
     */
    public function view(int $customerId): JsonResponse
    {
        $customer = $this->customerService->getCustomerAsArray($customerId);
        return new JsonResponse($customer);
    }
}