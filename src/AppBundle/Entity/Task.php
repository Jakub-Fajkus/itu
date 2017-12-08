<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    public const LOWEST_PRIORITY = 0;

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
     * @var int
     *
     * @ORM\Column(name="priority", type="smallint")
     */
    private $priority = self::LOWEST_PRIORITY;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="due", type="datetime", nullable=true)
     */
    private $due;

    /**
     * @var Project|null
     *
     * @ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="tasks")
     */
    private $project;

    /**
     * @var ArrayCollection
     *
     * @ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="tasks")
     */
    private $tags;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, name="task_order")
     */
    private $order = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="completed", type="boolean", nullable=false)
     */
    private $completed = false;


    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->due = new \DateTime();
    }


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
     * @return Task
     */
    public function setName(string $name): Task
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
     * Set priority
     *
     * @param integer $priority
     *
     * @return Task
     */
    public function setPriority(int $priority): Task
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Task
     */
    public function setCreatedAt(\DateTime $createdAt): Task
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set due
     *
     * @param null|\DateTime $due
     *
     * @return Task
     */
    public function setDue(?\DateTime $due): Task
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due
     *
     * @return \DateTime
     */
    public function getDue(): ?\DateTime
    {
        return $this->due;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * Pro oddeleni od projektu staci zavolat s null
     *
     * @param Project|null $project
     *
     * @return Task
     */
    public function setProject(?Project $project): Task
    {
        $this->project = $project;

        if ($project) {
            $project->addTask($this);
        }

        return $this;
    }

    /**
     * @return Collection<Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }


    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag): Task
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addTask($this);
        }

        return $this;
    }


    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag): Task
    {
        $this->tags->remove($tag);
        $tag->removeTask($this);

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
     * @return Task
     */
    public function setOrder(int $order): Task
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return Task
     */
    public function setCompleted(bool $completed): Task
    {
        $this->completed = $completed;

        return $this;
    }
}
