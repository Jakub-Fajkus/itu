<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TaskType;

/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller
{

    /**
     * Lists all task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('AppBundle:Task')->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'jsController' => 'TaskController',
            'jsAction' => 'indexAction',
        ]);
    }

    /**
     * @Route("/new-form", name="task_new_form")
     * @Method("GET")
     */
    public function getNewFormAction()
    {
        $form = $this->createForm(TaskType::class, null, ['action' => $this->generateUrl('task_new')]);

        $html = $this->renderView('task/newForm.html.twig', ['form' => $form->createView()]);

//        return new Response($html);

        return new JsonResponse(['html' => $html]);
    }

    /**
     * @Route("/{id}/edit-form/", name="task_edit_form")
     * @Method("GET")
     */
    public function getEditFormAction(Task $task)
    {
        $form = $this->createForm(TaskType::class, $task, ['action' => $this->generateUrl('task_edit', ['id' => $task->getId()])]);

        $html = $this->renderView('task/editForm.html.twig', ['form' => $form->createView()]);

        return new JsonResponse(['html' => $html]);
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $tasks = $task->getProject()->getTasks()->toArray();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $emptyProject = $em->getRepository(Project::class)
                ->findOneBy(['user' => $this->getUser(), 'isDefault' => true]);

            $tasks[] = $task;
            $this->setTasksOrder($tasks, $task->getProject(), $emptyProject);

            $html = $this->renderView('project/show.html.twig', ['project' => $task->getProject()]);

            return new JsonResponse(
                ['html' => $html, 'status' => ProjectController::SUCCESS, 'flashMessage' => 'Úspěšně uloženo']
            );
        } else {
            $msg = '';
            foreach ($form->getErrors(true) as $error) {
                $msg .= $error->getMessage();
            }

            return new JsonResponse(['status' => ProjectController::ERROR, 'flashMessage' => $msg]);
        }
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
            'jsController' => 'TaskController',
            'jsAction' => 'showAction',
        ]);
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        $editForm = $this->createForm(TaskType::class, $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $html = $this->renderView('project/show.html.twig', ['project' => $task->getProject()]);

            return new JsonResponse(
                ['html' => $html, 'status' => ProjectController::SUCCESS, 'flashMessage' => 'Úspěšně uloženo']
            );
        } else {
            $msg = '';
            foreach ($editForm->getErrors(true) as $error) {
                $msg .= $error->getMessage();
            }

            return new JsonResponse(['status' => ProjectController::ERROR, 'flashMessage' => $msg]);
        }
    }

    /**
     * @Route("/{id}/completed", name="task_set_completed")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Task    $task
     * @return JsonResponse
     */
    public function setCompletedActions(Request $request, Task $task)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);

        $emptyProject = $em->getRepository(Project::class)
            ->findOneBy(['user' => $this->getUser(), 'isDefault' => true]);

        $completed = (bool)$data['completed'];

        $task->setCompleted($completed);

        $project = $task->getProject();
        $tasks = $project->getTasks()->remove($task);
        $tasks = $tasks->toArray();

        if ($completed === true) {
            //hotovy task dame na konec
            $tasks[] = $task;
        } else {
            //nehotovy dame na zacatek
            $tasks = array_unshift($tasks, $task);
        }

        $this->setTasksOrder($tasks, $project, $emptyProject);


        $em->persist($task);
        $em->flush();

        return new JsonResponse(['flashMessage' => 'Označeno jako hotovo', 'status' => ProjectController::SUCCESS]);
    }


    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Task[]  $tasks
     * @param Project $project
     * @param Project $emptyProject
     */
    public function setTasksOrder($tasks, $project, $emptyProject)
    {
        $em = $this->getDoctrine()->getManager();

        $order = 0;
        foreach ($tasks as $task) {
            $task->setOrder($order++);

            $em->persist($task);
            $em->persist($project);
        }

        $em->persist($emptyProject);
        $em->flush();
    }
}
