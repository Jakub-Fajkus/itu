<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;

/**
 * Class ProjectReorder
 *
 * Zajistuje precislovani projektu v databazi
 */
class ProjectReorder
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param int[] $ids
     */
    public function reorder(array $ids): void
    {
        /** @var Project[] $projects */
        $projects = $this->entityManager->getRepository(Project::class)->getByIds($ids);
        $idsOrder = array_flip($ids);

        usort($projects, function (Project $a, Project $b) use ($idsOrder) {
            return $idsOrder[$a->getId()] <=> $idsOrder[$b->getId()];
        });

        $order = 0;
        foreach ($projects as $project) {
            $project->setOrder($order);
        }
    }
}