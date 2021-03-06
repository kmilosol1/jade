<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\JobRepository")
 * @ORM\Table(name="job")
 * @Annotation\Name("job")
 */
class Job
{

    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 0;

    const PERMISSION_MANAGE = 'JOB.MANAGE';
    const PERMISSION_EDIT = 'JOB.EDIT';
    const PERMISSION_VIEW = 'JOB.VIEW';

    const CONTRACT_TYPE_VARIABLE = 1;
    const CONTRACT_TYPE_FIXED = 2;

    const CONTRACT_CURRENCIES = [
        'INR' => 'INR',
        'EUR' => 'EUR',
        'USD' => 'USD',
        'GBP' => 'GBP'
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Annotation\Exclude()
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":255}})
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"job.name"})
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Annotation\Required(false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1}})
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"job.description"})
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Annotation\Required(false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1}})
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"job.comments"})
     */
    protected $comments;

    /**
     * @ORM\Column(type="integer")
     * @Annotation\Required(false)
     * @Annotation\AllowEmpty(true)
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Options({"label":"job.contract-type"})
     */
    protected $contractType;

    /**
     * @ORM\Column(type="float")
     * @Annotation\Required(false)
     * @Annotation\Validator({"name":"Float"})
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"job.contract-rate"})
     */
    protected $contractRate;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Required(false)
     * @Annotation\AllowEmpty(true)
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Options({"label":"job.contract-currency"})
     */
    protected $contractCurrency;

    /**
     * @ORM\Column(type="datetime")
     * @Annotation\Exclude()
     */
    protected $creationTime;

    /**
     * @ORM\Column(type="integer")
     * @Annotation\Exclude()
     */
    protected $status;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Label")
     * @ORM\JoinTable(name="job_label")
     * @Annotation\Required(false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"multiple":"multiple"})
     * @Annotation\Options({"label":"label.labels", "use_hidden_element":"true"})
     * @see https://github.com/zendframework/zendframework/issues/7298
     */
    protected $labels;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Job\File", mappedBy="job", cascade={"remove"})
     * @ORM\OrderBy({"creationTime" = "DESC"})
     * @Annotation\Required(false)
     * @see http://future500.nl/articles/2013/09/more-on-one-to-manymany-to-one-associations-in-doctrine-2/
     */
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Job\Log", mappedBy="job", cascade={"remove"})
     * @ORM\OrderBy({"creationTime" = "DESC"})
     * @Annotation\Required(false)
     * @see http://future500.nl/articles/2013/09/more-on-one-to-manymany-to-one-associations-in-doctrine-2/
     */
    protected $logs;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Options({"label":"user.role-customer", "use_hidden_element":"true"})
     * @Annotation\Required(false)
     * @see http://www.itgo.me/a/x1674750047556883894/calculate-changeset-for-object-that-have-proxy-entity-in-property-for-logging-pu
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Job\Permission", mappedBy="entity", cascade={"remove", "persist"})
     * @Annotation\Exclude()
     */
    protected $permissions;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"common.save"})
     */
    public $submit;

    /**
     * @Annotation\Type("Zend\Form\Element\Csrf")
     */
    public $csrf;

    public function __construct() {
        $this->labels = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->privileges = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getCreationTime()
    {
        return $this->creationTime;
    }

    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
    }

    public function getContractType()
    {
        return $this->contractType;
    }

    public function setContractType($contractType)
    {
        $this->contractType = (int)$contractType;
    }

    public function getContractCurrency()
    {
        return $this->contractCurrency;
    }

    public function setContractCurrency($contractCurrency)
    {
        $this->contractCurrency = $contractCurrency;
    }

    public function getContractRate()
    {
        $filter = new \Zend\I18n\Filter\NumberFormat();
        return $filter->filter($this->contractRate);
    }

    public function setContractRate($contractRate)
    {
        $filter = new \Zend\I18n\Filter\NumberParse();
        $this->contractRate = $filter->filter($contractRate);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    public function addLabels(ArrayCollection $labels)
    {
        foreach ($labels as $label) {
            $this->labels->add($label);
        }
    }

    public function removeLabels(ArrayCollection $labels)
    {
        foreach ($labels as $label) {
            $this->labels->removeElement($label);
        }
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function addFile(\Application\Entity\Job\File $file)
    {
        $this->files->add($file);
    }

    public function removeFile(\Application\Entity\Job\File $file)
    {
        $this->files->removeElement($file);
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    public function addLog(\Application\Entity\Job\Log $logs)
    {
        $this->logs->add($logs);
    }

    public function removeLog(\Application\Entity\Job\Log $logs)
    {
        $this->logs->removeElement($logs);
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    public function addPermission(Permission $permission)
    {
        $this->permissions->add($permission);
    }

    public function removePermission(Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    public function getUserPermissions(User $user)
    {
        $permissions = array();
        if ($user->getRole() == User::ROLE_ADMINISTRATOR) {
            $permission = new Job\Permission;
            $permission->setUser($user);
            $permission->setName(Job::PERMISSION_MANAGE);
            $permission->setJob($this);
            $permissions[] = $permission;
        }
        foreach ($this->permissions as $permission) {
            if ($permission->getUser()->getId() == $user->getId()) {
                $permissions[] = $permission;
            }
        }
        return $permissions;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function getOwner()
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getName() == Job::PERMISSION_MANAGE) {
                return $permission->getUser();
            }
        }
    }
}