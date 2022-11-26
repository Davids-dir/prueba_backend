<?php

namespace App\Service;

use App\Entity\Lead;
use App\Entity\Project;
use App\Repository\LeadRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectService extends AbstractController
{
    public function __construct(ProjectRepository $projectRepository, LeadRepository $leadRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepositoy = $projectRepository;
        $this->leadRepository = $leadRepository;
        $this->entityManager = $entityManager;
    }

    /* Métodos GET */
    public function findProjectById(Request  $request): JsonResponse
    {
        $project = $this->projectRepositoy->findOneBy(['id' => $request->get('id')]);

        if (!$project) {
            $result = 'No se ha encontrado ningún resultado.';
            $code = 404;
        } else {
            $result = [
                'idProyecto' => $project->getId(),
                'nombre' => $project->getName(),
                'pais' => $project->getCountry(),
                'activo' => $project->isActive()
            ];

            $code = 200;
        }

        return $this->json([
            'respuesta' => $result,
        ], $code);
    }

    public function findAllProjects(): JsonResponse
    {
        $result = [];
        $projects = $this->projectRepositoy->findAll();

        foreach ($projects as $project) {
            $result[] = ['idProyecto' => $project->getId(), 'nombre' => $project->getName()];
        }

        return $this->json([
            'respuesta' => $result,
        ], 200);
    }

    public function findAllLeadsByProjectId(Request $request): JsonResponse
    {
        $projects = $this->projectRepositoy->findOneBy(['id' => $request->get('id')]);

        foreach ($projects->getLeads() as $lead) {
            $result[] = [
                'idLead' => $lead->getId(),
                'nombre' => $lead->getName(),
                'email' => $lead->getEmail(),
                'telefono' => $lead->getPhone()
            ];
        }

        return $this->json([
            'respuesta' => $result,
        ], 200);
    }
    /* Fin métodos GET */

    /* Métodos POST */
    public function createNewProject(Request $request): JsonResponse
    {
        $bodyData = $request->toArray();
        $responseMessage = 'El proyecto se ha dado de alta correctamente.';

        $project = $this->projectRepositoy->findOneBy(['name' => $bodyData['nombre']]);

        if (!is_null($project)) {
            $project
                ->setCountry($bodyData['pais'])
                ->setActive($bodyData['activo']);

            $this->projectRepositoy->update($project);
            $code = 201;
        } else {
            $project = new Project();
            $project
                ->setName($bodyData['nombre'])
                ->setCountry($bodyData['pais'])
                ->setActive($bodyData['activo']);

            try {
                $this->projectRepositoy->save($project, true);
                $code = 201;
            } catch (\Exception $exception) {
                $responseMessage = $exception->getMessage();
                $code = 400;
            }
        }

        return $this->json([
            'respuesta' => $responseMessage,
        ], $code);
    }

    public function createNewLead(Request $request): JsonResponse
    {
        $bodyData = $request->toArray();
        $responseMessage = 'El lead se ha dado de alta correctamente.';

        $lead = $this->leadRepository->findOneBy(['phone' => $bodyData['telefono']]);
        $project = $this->projectRepositoy->findOneBy(['id' => $request->get('id')]);

        if (!is_null($lead)) {
            $lead
                ->setName($bodyData['nombre'])
                ->setEmail($bodyData['email'])
                ->setPhone($bodyData['telefono'])
                ->setProject($project);

            $this->leadRepository->update($lead);
            $code = 201;
        } else {
            $lead = new Lead();
            $lead
                ->setName($bodyData['nombre'])
                ->setEmail($bodyData['email'])
                ->setPhone($bodyData['telefono'])
                ->setProject($project);

            try {
                $this->leadRepository->save($lead, true);
                $code = 201;
            } catch (\Exception $exception) {
                $responseMessage = $exception->getMessage();
                $code = 400;
            }
        }

        return $this->json([
            'respuesta' => $responseMessage,
        ], $code);
    }
    /* Fin métodos POST */

    /* Métodos PUT */
    public function updateProject(Request $request): JsonResponse
    {
        $bodyData = $request->toArray();
        $project = $this->projectRepositoy->findOneBy(['id' => $request->get('id')]);

        if (!$project) {
            $result = 'No se ha encontrado ningún resultado.';
            $code = 404;
        } else {
            try {
                $project
                    ->setCountry($bodyData['pais'])
                    ->setActive($bodyData['activo']);

                $this->projectRepositoy->update($project);
                $result = 'El proyecto se ha editado correctamente.';
                $code = 200;
            } catch (\Exception $exception) {
                $result = $exception->getMessage();
                $code = 400;
            }
        }

        return $this->json([
            'respuesta' => $result,
        ], $code);
    }
    /* Fin métodos PUT */

    /* Métodos DELETE */
    public function deleteProject(Request $request): JsonResponse
    {
        $project = $this->projectRepositoy->findOneBy(['id' => $request->get('id')]);

        if (!$project) {
            $result = 'No se ha encontrado ningún resultado.';
            $code = 404;
        } else {
            $this->projectRepositoy->remove($project, true);
            $result = 'El proyecto se ha borrado correctamente.';
            $code = 200;
        }

        return $this->json([
            'respuesta' => $result,
        ], $code);

    }
    /* Fin métodos DELETE */
}