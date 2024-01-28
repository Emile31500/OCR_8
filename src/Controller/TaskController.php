<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function list(TaskRepository $taskRepository): Response
    {
         
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findByIsDone(false)]);

    }

    /**
     * @Route("/tasks/done", name="done_task_list")
     */
    public function listDone(TaskRepository $taskRepository): Response
    {
        return $this->render('task/done.html.twig', ['tasks' => $taskRepository->findByIsDone(true)]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request, UserRepository $userRepo): Response
    {
        if ($user = $this->getUser()){

            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $task->setUser($user);
                $em->persist($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a été bien été ajoutée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/create.html.twig', ['form' => $form->createView()]);
            
        } else {

            return $this->redirect('/');
        }
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function edit(Task $task, Request $request): Response
    {

        $this->denyAccessUnlessGranted('task_edit', $task);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);

    
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task): Response
    {
        $newStatus = !$task->isDone();
        $task->toggle($newStatus);
        $this->getDoctrine()->getManager()->flush();

        if ($newStatus) {

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        } else {

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));

        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}", name="task_delete", methods="DELETE")
     */
    public function deleteTask(Task $task): Response
    {
        $this->denyAccessUnlessGranted('task_delete', $task);
        
         if ($task->isDone()) {

            $route = 'done_task_list';

        } else {

            $route = 'task_list';
            
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');
        return $this->redirectToRoute($route);
    }

}
