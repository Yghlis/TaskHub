<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/api/tasks", name="api_task_list", methods={"GET"})
     */
    public function list(TaskRepository $taskRepository, SerializerInterface $serializer): Response
    {
        $tasks = $taskRepository->findAll();

        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'nom' => $task->getNom(),
                'description' => $task->getDescription(),
                'date' => $task->getDate(),
                'completed' => $task->isCompleted(),
            ];
        }

        $json = $serializer->serialize($data, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/tasks", name="api_task_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setNom($data['nom']);
        $task->setDescription($data['description']);
        $task->setDate(new \DateTime($data['date']));
        $task->setCompleted($data['completed']);

        $entityManager->persist($task);
        $entityManager->flush();

        $json = $serializer->serialize($task, 'json');

        return new Response($json, 201, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/tasks/{id}", name="api_task_show", methods={"GET"})
     */
    public function show(Task $task, SerializerInterface $serializer): Response
    {
        $data = [
            'id' => $task->getId(),
            'nom' => $task->getNom(),
            'description' => $task->getDescription(),
            'completed' => $task->isCompleted(),
        ];

        $json = $serializer->serialize($data, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/tasks/{id}", name="api_task_update", methods={"PUT"})
     */
    public function update(Task $task, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $task->setNom($data['nom']);
        $task->setDescription($data['description']);
        $task->setDate(new \DateTime($data['date']));
        $task->setCompleted($data['completed']);

        $entityManager->flush();

        $json = $serializer->serialize($task, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/tasks/{id}", name="api_task_delete", methods={"DELETE"})
     */
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return new Response(null, 204);
    }
}
