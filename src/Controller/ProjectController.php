<?php

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    #[Route('/proyecto', name: 'app_project', methods: ['GET', 'POST'])]
    public function allProjects(Request $request): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->projectService->findAllProjects();
        } else {
            return $this->projectService->createNewProject($request);
        }
    }

    #[Route('/proyecto/{id}', name: 'app_project_id', methods: ['GET', 'DELETE', 'PUT'])]
    public function projectsById(Request $request): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->projectService->findProjectById($request);
        } elseif ($request->isMethod('PUT')) {
            return $this->projectService->updateProject($request);
        } else {
            return $this->projectService->deleteProject($request);
        }
    }

    #[Route('/proyecto/{id}/leads', name: 'app_project_id_lead', methods: ['GET', 'POST'])]
    public function getLeadsByProjectId(Request $request): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->projectService->findAllLeadsByProjectId($request);
        } else {
            return $this->projectService->createNewLead($request);
        }
    }
}
