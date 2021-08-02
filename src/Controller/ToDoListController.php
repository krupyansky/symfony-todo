<?php

namespace App\Controller;

use App\Entity\Task;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToDoListController
 * @package App\Controller
 */
class ToDoListController extends AbstractController
{
    #[Route('/api/v1/list', name: 'show_tasks', methods: 'GET')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'List of tasks!',
            'path' => 'src/Controller/ToDoListController.php',
        ]);
    }

    #[Route('/api/v1/create', name: 'create_task', methods: 'POST')]
    public function create(Request $request): Response
    {
        $title = $request->request->get('title');
        $title = trim($title);

        if (!$title) {
            throw new DomainException('Title of a task must not be empty.');
        }

        $em = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title);

        $em->persist($task);
        $em->flush();

        return $this->json([
            'id' => $task->getId(),
        ]);
    }

    #[Route('/api/v1/switch-status/{id}', name: 'switch_status', methods: 'PATCH')]
    public function switchStatus(int $id): Response
    {
        return $this->json([
            'message' => sprintf('Switch status of the task! %d', $id),
            'path' => 'src/Controller/ToDoListController.php',
        ]);
    }

    #[Route('/api/v1/delete/{id}', name: 'delete_task', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        return $this->json([
            'message' => sprintf('Delete the task! %d', $id),
            'path' => 'src/Controller/ToDoListController.php',
        ]);
    }
}
