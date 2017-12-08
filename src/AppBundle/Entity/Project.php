<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name = '';


    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="project")
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $tasks;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, name="project_order")
     */
    private $order = 0;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }


    /**
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task): Project
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }


    /**
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): Project
    {
        $this->tasks->remove($task);

        return $this;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }


    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return Project
     */
    public function setOrder(int $order): Project
    {
        $this->order = $order;

        return $this;
    }
}