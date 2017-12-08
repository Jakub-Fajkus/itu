<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project controller.
 *
 * @Route("project")
 */
class ProjectController extends Controller
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const INFO = 'info';

//todo: vytvorit routy, vracejici jen html formulare(jen <form> pro project a task
    /**
     * Lists all project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('AppBundle:Project')->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'jsController' => 'ProjectController',
            'jsAction' => 'indexAction',
        ]);
    }

    /**
     * @Route("/new-form", name="project_new_form")
     * @Method("GET")
     */
    public function getNewFormAction()
    {
        $form = $this->createForm(ProjectType::class);

        return $this->render('project/newForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit-form/", name="project_edit_form")
     * @Method("GET")
     */
    public function getEditFormAction(Project $project)
    {
        $form = $this->createForm(ProjectType::class, $project);

        return $this->render('project/editForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Creates a new project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'jsController' => 'ProjectController',
            'jsAction' => 'newAction',
        ]);
    }

    /**
     * Finds and displays a project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'delete_form' => $deleteForm->createView(),
            'jsController' => 'ProjectController',
            'jsAction' => 'showAction',
        ]);
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm(ProjectType::class, $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_edit', ['id' => $project->getId()]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'jsController' => 'ProjectController',
            'jsAction' => 'editAction',
        ]);
    }


    /**
     * Zmeni poradi vsech projektu
     *
     * @Route("/reorder/",  name="project_reorder")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reorderProjects(Request $request)
    {
        $ids = json_decode($request->getContent());

        //zmenime data entit - precislujeme je
        $this->reorderProjectsByIds($ids);

        return new JsonResponse(['flashMessage' => 'Pořadí změněno', 'status' => self::SUCCESS]);
    }

    /**
     * Zmeni poradi tasku v urcitem projektu
     *
     * @Route("/{id}/reorder", name="project_reorder_tasks")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reorderTasks(Request $request, Project $project)
    {
        $ids = json_decode($request->getContent());

        //zmenime data entit - precislujeme je
        $this->reorderTasksInProject($project, $ids);

        return new JsonResponse(['flashMessage' => 'Pořadí změněno', 'status' => self::SUCCESS]);
    }


    /**
     * Deletes a project entity.
     *
     * @Route("/{id}", name="project_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * Creates a form to delete a project entity.
     *
     * @param Project $project The project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', ['id' => $project->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param int[] $ids
     */
    private function reorderProjectsByIds(array $ids): void
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Project[] $projects */
        $projects = $em->getRepository(Project::class)->getByIds($ids);
        $idsOrder = array_flip($ids);

        usort($projects, function (Project $a, Project $b) use ($idsOrder) {
            return $idsOrder[$a->getId()] <=> $idsOrder[$b->getId()];
        });

        $order = 0;
        foreach ($projects as $project) {
            $project->setOrder($order++);
            $em->persist($project);
        }

        $em->flush();
    }

    /**
     * @param Project $project
     * @param array   $ids
     */
    private function reorderTasksInProject(Project $project, array $ids): void
    {
        $em = $this->getDoctrine()->getManager();

        $emptyProject = $em->getRepository(Project::class)->findOneBy(['user' => $this->getUser(), 'isDefault' => true]);

        //vytahnu vsechny tasky z projektu -> prevedeme na pole, abysme se zbavili reference na kolekci
        /** @var Task[] $currentProjectTasks */
        $currentProjectTasks = $project->getTasks()->toArray();

        $currentProjectTasksIds = [];

        //pokud aktualni task neni v poli id, ktere poslal klient
        //tak byl presunut do jineho, nebo do prazdneho
        //takze jej priradime do prazdneho projektu
        for ($i = 0, $count = \count($currentProjectTasks); $i < $count; $i++) {
            $currentProjectTask = $currentProjectTasks[$i];
            $currentProjectTasksIds[] = $currentProjectTask->getId();

            if (false === in_array($currentProjectTask->getId(), $ids, false)) {
                /** @var Task|false $last */
                $last = $emptyProject->getTasks()->last();

                //ziskame order posledniho prvku v prazdnem projektu
                $lastOrder = ($last === false) ? 0 : $last->getOrder() + 1;

                $currentProjectTask->setOrder($lastOrder);
                $currentProjectTask->setProject($emptyProject);

                unset($currentProjectTasks[$i]);
            }
        }

        //podivame se, jestli se nepridalo nejake id
        //pokud ano, pridame tento task do pole tasku, ktere jsou v tomto projektu
        $addedIds = array_diff($ids, $currentProjectTasksIds);
        if (!empty($addedIds)) {
            foreach ($addedIds as $addedId) {
                $task = $em->getRepository(Task::class)->find($addedId);
                $task->setProject($project);
                $currentProjectTasks[] = $task;
            }
        }


        $idsOrder = array_flip($ids);

        //seradime tasky podle jejich id
        usort($currentProjectTasks, function (Task $a, Task $b) use ($idsOrder) {
            return $idsOrder[$a->getId()] <=> $idsOrder[$b->getId()];
        });

        $order = 0;
        foreach ($currentProjectTasks as $task) {
            $task->setOrder($order++);

            $em->persist($task);
            $em->persist($project);
        }

        $em->persist($emptyProject);
        $em->flush();
    }
}
