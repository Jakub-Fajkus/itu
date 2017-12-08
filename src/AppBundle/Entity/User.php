<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * User
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity
 * @ORM\MappedSuperclass()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="AppBundle\Entity\Project", mappedBy="user")
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $projects;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }


    /**
     * @return ArrayCollection<Project>
     */
    public function getProjects()
    {
        return $this->projects;
    }


    /**
     * @param Project $project
     * @return $this
     */
    public function addProject(Project $project)
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }

        return $this;
    }


    /**
     * @param Project $project
     * @return $this
     */
    public function removeProject(Project $project)
    {
        $this->projects->remove($project);

        return $this;
    }

}
