<?php

namespace App\Controller;

use App\Entity\Task;
use DateTime;
use App\Form\AddTaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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



        $data = $request->request->all();
        $nom = $data['add_task']['nom'];
        $description = $data['add_task']['description'];

        $completed = false;

        $date = sprintf(
            '%d-%d-%d',
            $data['add_task']['date']['year'],
            $data['add_task']['date']['month'],
            $data['add_task']['date']['day']
        );






        $task = new Task();
        $task->setNom($nom);
        $task->setDescription($description);
        $task->setDate(new DateTime($date));$task->setDate(DateTime::createFromFormat('Y-m-d', $date));
        $task->setCompleted($completed);

        $entityManager->persist($task);
        $entityManager->flush();


        $json = $serializer->serialize($task, 'json');
        return new Response($json, 201, [
            'Content-Type' => 'application/json'
        ]);
        return $this->redirectToRoute('app_home');
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
        $data = $request->request->all();
        $nom = $data['nom'];
        $description = $data['description'];
        $completed = false;


        $task->setNom($nom);
        $task->setDescription($description);
        $task->setDate(new \DateTime($data['date']));
        $task->setCompleted($completed);

        $entityManager->flush();

        $json = $serializer->serialize($task, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/task/{id}", name="api_task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): JsonResponse
    {

        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
