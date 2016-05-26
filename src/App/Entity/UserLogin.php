<?php
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
     * @var integer
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
     *      type="integer",
     *      nullable=false,
     *  )
     * @ORM\Id()
     * @ORM\GeneratedValue(
     *      strategy="IDENTITY",
     *  )
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
     * @return  integer
     */
    public function getId()
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
     * Simple method to get 'string' presentation about the current record.
     *
     * @return  string
     */
    public function getRecordTitle()
    {
        // TODO: Implement getRecordTitle() method.
    }

    /**
     * @param User $user
     *
     * @return UserLogin
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param string $ip
     *
     * @return UserLogin
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return UserLogin
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $agent
     *
     * @return UserLogin
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @param string $clientType
     *
     * @return UserLogin
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * @param string $clientName
     *
     * @return UserLogin
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * @param string $clientShortName
     *
     * @return UserLogin
     */
    public function setClientShortName($clientShortName)
    {
        $this->clientShortName = $clientShortName;

        return $this;
    }

    /**
     * @param string $clientVersion
     *
     * @return UserLogin
     */
    public function setClientVersion($clientVersion)
    {
        $this->clientVersion = $clientVersion;

        return $this;
    }

    /**
     * @param string $clientEngine
     *
     * @return UserLogin
     */
    public function setClientEngine($clientEngine)
    {
        $this->clientEngine = $clientEngine;

        return $this;
    }

    /**
     * @param string $osName
     *
     * @return UserLogin
     */
    public function setOsName($osName)
    {
        $this->osName = $osName;

        return $this;
    }

    /**
     * @param string $osShortName
     *
     * @return UserLogin
     */
    public function setOsShortName($osShortName)
    {
        $this->osShortName = $osShortName;

        return $this;
    }

    /**
     * @param string $osVersion
     *
     * @return UserLogin
     */
    public function setOsVersion($osVersion)
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * @param string $osPlatform
     *
     * @return UserLogin
     */
    public function setOsPlatform($osPlatform)
    {
        $this->osPlatform = $osPlatform;

        return $this;
    }

    /**
     * @param string $deviceName
     *
     * @return UserLogin
     */
    public function setDeviceName($deviceName)
    {
        $this->deviceName = $deviceName;

        return $this;
    }

    /**
     * @param string $brandName
     *
     * @return UserLogin
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;

        return $this;
    }

    /**
     * @param string $model
     *
     * @return UserLogin
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
