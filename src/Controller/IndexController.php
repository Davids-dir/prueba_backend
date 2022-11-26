<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/status', name: 'app_index', methods: ['GET'])]
    public function checkApiStatus(): JsonResponse
    {
        return $this->json([
            'respuesta' => 'El servicio esta online',
        ], 200);
    }
}
