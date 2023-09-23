<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $taskRepository)
    {
         
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findByIsDone(false)]);

    }

    /**
     * @Route("/tasks/done", name="done_task_list")
     */
    public function listDoneAction(TaskRepository $taskRepository)
    {
        return $this->render('task/done.html.twig', ['tasks' => $taskRepository->findByIsDone(true)]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, UserRepository $userRepo)
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
    public function editAction(Task $task, Request $request)
    {
        if ($user = $this->getUser()) {
            
            if ($task->getUser() === $user || $this->isGranted('ROLE_ADMIN')){

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

            } else {

                return $this->redirect('/');

            }

        } else {
            
            return $this->redirect('/');
        }
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {

        if ($user = $this->getUser()){

            if($task->getUser() !== null){

                if($user === $task->getUser() || $this->isGranted("ROLE_ADMIN")){

                    $em = $this->getDoctrine()->getManager();
                    $em->remove($task);
                    $em->flush();

                    $this->addFlash('success', 'La tâche a bien été supprimée.');
                    return $this->redirectToRoute('task_list');

                } else {

                    $this->addFlash('notice', 'Cette tâche ne vous appartient pas, vous devez être administrateur pour la supprimer');
                    return $this->redirectToRoute('task_list');

                }

            } else if ($this->isGranted("ROLE_ADMIN")) {

                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a bien été supprimée.');
                return $this->redirectToRoute('task_list');

            } else {
                
                $this->addFlash('notice', 'Cette tâche est anonyme, vous devez être administrateur pour la supprimer');
                return $this->redirectToRoute('task_list');

            }

        } else {
            $url = '/';
            return $this->redirect($url);
        }
    }
}
