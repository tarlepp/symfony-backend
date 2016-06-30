<?php
declare(strict_types=1);
/**
 * /src/App/Entity/UserLogin.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserLogin class
 *
 * @ORM\Table(
 *      name="user_login",
 *      indexes={
 *          @ORM\Index(name="user", columns={"user_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\UserLogin"
 *  )
 *
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserLogin implements EntityInterface
{
    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.id",
     *      "User.userLogins",
     *  })
     *
     * @ORM\Column(
     *      name="id",
     *      type="guid",
     *      nullable=false,
     *  )
     * @ORM\Id()
     */
    private $id;

    /**
     * @var \App\Entity\User
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.user",
     *  })
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\User",
     *      inversedBy="userLogins",
     *  )
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="user_id",
     *          referencedColumnName="id",
     *      ),
     *  })
     */
    private $user;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.ip",
     *  })
     *
     * @ORM\Column(
     *      name="ip",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $ip;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.host",
     *  })
     *
     * @ORM\Column(
     *      name="host",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $host;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.agent",
     *  })
     *
     * @ORM\Column(
     *      name="agent",
     *      type="text",
     *      nullable=false,
     *  )
     */
    private $agent;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.clientType",
     *  })
     *
     * @ORM\Column(
     *      name="client_type",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $clientType;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.clientName",
     *  })
     *
     * @ORM\Column(
     *      name="client_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $clientName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.clientShortName",
     *  })
     *
     * @ORM\Column(
     *      name="client_short_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $clientShortName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.clientVersion",
     *  })
     *
     * @ORM\Column(
     *      name="client_version",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $clientVersion;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.clientEngine",
     *  })
     *
     * @ORM\Column(
     *      name="client_engine",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $clientEngine;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.osName",
     *  })
     *
     * @ORM\Column(
     *      name="os_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $osName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.osShortName",
     *  })
     *
     * @ORM\Column(
     *      name="os_short_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $osShortName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.osVersion",
     *  })
     *
     * @ORM\Column(
     *      name="os_version",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $osVersion;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.osPlatform",
     *  })
     *
     * @ORM\Column(
     *      name="os_platform",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $osPlatform;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.deviceName",
     *  })
     *
     * @ORM\Column(
     *      name="device_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $deviceName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.brandName",
     *  })
     *
     * @ORM\Column(
     *      name="brand_name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $brandName;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.model",
     *  })
     *
     * @ORM\Column(
     *      name="model",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $model;

    /**
     * @var \DateTime
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserLogin",
     *      "UserLogins",
     *      "UserLogin.loginTime",
     *  })
     *
     * @ORM\Column(
     *      name="login_time",
     *      type="datetime",
     *      nullable=false,
     *  )
     */
    private $loginTime;

    /**
     * UserLogin constructor.
     *
     * @return UserLogin
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * @return  string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @return string
     */
    public function getClientShortName()
    {
        return $this->clientShortName;
    }

    /**
     * @return string
     */
    public function getClientVersion()
    {
        return $this->clientVersion;
    }

    /**
     * @return string
     */
    public function getClientEngine()
    {
        return $this->clientEngine;
    }

    /**
     * @return string
     */
    public function getOsName()
    {
        return $this->osName;
    }

    /**
     * @return string
     */
    public function getOsShortName()
    {
        return $this->osShortName;
    }

    /**
     * @return string
     */
    public function getOsVersion()
    {
        return $this->osVersion;
    }

    /**
     * @return string
     */
    public function getOsPlatform()
    {
        return $this->osPlatform;
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @return string
     */
    public function getBrandName()
    {
        return $this->brandName;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \DateTime
     */
    public function getLoginTime() : \DateTime
    {
        return $this->loginTime;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserLogin
     */
    public function setUser(UserInterface $user) : UserLogin
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param string $ip
     *
     * @return UserLogin
     */
    public function setIp(string $ip) : UserLogin
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return UserLogin
     */
    public function setHost(string $host) : UserLogin
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $agent
     *
     * @return UserLogin
     */
    public function setAgent(string $agent) : UserLogin
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @param string $clientType
     *
     * @return UserLogin
     */
    public function setClientType(string $clientType) : UserLogin
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * @param string $clientName
     *
     * @return UserLogin
     */
    public function setClientName(string $clientName) : UserLogin
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * @param string $clientShortName
     *
     * @return UserLogin
     */
    public function setClientShortName(string $clientShortName) : UserLogin
    {
        $this->clientShortName = $clientShortName;

        return $this;
    }

    /**
     * @param string $clientVersion
     *
     * @return UserLogin
     */
    public function setClientVersion(string $clientVersion) : UserLogin
    {
        $this->clientVersion = $clientVersion;

        return $this;
    }

    /**
     * @param string $clientEngine
     *
     * @return UserLogin
     */
    public function setClientEngine(string $clientEngine) : UserLogin
    {
        $this->clientEngine = $clientEngine;

        return $this;
    }

    /**
     * @param string $osName
     *
     * @return UserLogin
     */
    public function setOsName(string $osName) : UserLogin
    {
        $this->osName = $osName;

        return $this;
    }

    /**
     * @param string $osShortName
     *
     * @return UserLogin
     */
    public function setOsShortName(string $osShortName) : UserLogin
    {
        $this->osShortName = $osShortName;

        return $this;
    }

    /**
     * @param string $osVersion
     *
     * @return UserLogin
     */
    public function setOsVersion(string $osVersion) : UserLogin
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * @param string $osPlatform
     *
     * @return UserLogin
     */
    public function setOsPlatform(string $osPlatform) : UserLogin
    {
        $this->osPlatform = $osPlatform;

        return $this;
    }

    /**
     * @param string $deviceName
     *
     * @return UserLogin
     */
    public function setDeviceName(string $deviceName) : UserLogin
    {
        $this->deviceName = $deviceName;

        return $this;
    }

    /**
     * @param string $brandName
     *
     * @return UserLogin
     */
    public function setBrandName(string $brandName) : UserLogin
    {
        $this->brandName = $brandName;

        return $this;
    }

    /**
     * @param string $model
     *
     * @return UserLogin
     */
    public function setModel(string $model) : UserLogin
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param \DateTime $loginTime
     *
     * @return UserLogin
     */
    public function setLoginTime(\DateTime $loginTime) : UserLogin
    {
        $this->loginTime = $loginTime;

        return $this;
    }
}
