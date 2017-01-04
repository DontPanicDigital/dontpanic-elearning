<?php

namespace DontPanic\Auth;

use DontPanic\Model\MainModel;
use Nette\Neon\Neon;
use Nette\Security\User;
use Nette\Utils\Strings;

class PresenterAuthorizationModel extends MainModel
{

    const CONFIGURATION_FILES_FOLDER = __DIR__ . '/../../config/presenterAuthorization/';

    const CONFIGURATION_FILE_EXTENSION = '.neon';

    /** @var User */
    private $user;

    /** @var string */
    private $name;

    /** @var string|null */
    private $action = null;

    /** @var null|string */
    private $configurationFile = null;

    /** @var array */
    private $autohrizationData = [];

    public $onAccessDenied;

    public $onAccessAllowed;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function check()
    {
        try {
            $this->setConfigurationFile();
            $this->setAutohrizationData();
            $this->findActionInConfiguration();
            $this->verifyUserAccess();
            $this->onAccessAllowed();
        } catch (AuthorizationFileNotFound $e) {
            $this->onAccessDenied();
        } catch (AuthorizationDataNotExists $e) {
            $this->onAccessDenied();
        } catch (AuthorizationActionNotExists $e) {
            $this->onAccessDenied();
        } catch (UserAuthorizationFailed $e) {
            $this->onAccessDenied();
        }
    }

    private function verifyUserAccess()
    {
        $hasPermission = false;
        foreach ($this->autohrizationData[$this->action] as $access) {
            if ($this->user->isAllowed($access['resource'], $access['privilege'])) {
                $hasPermission = true;
            }
        }
        if (!$hasPermission) {
            throw new UserAuthorizationFailed;
        }
    }

    private function findActionInConfiguration()
    {
        if (!array_key_exists($this->action, $this->autohrizationData)) {
            throw new AuthorizationActionNotExists;
        }
    }

    private function setAutohrizationData()
    {
        $authorizationData = file_get_contents($this->configurationFile);
        $data              = Neon::decode($authorizationData);

        if (!count($data)) {
            throw new AuthorizationDataNotExists;
        }

        $this->autohrizationData = $data;
    }

    private function setConfigurationFile()
    {
        $configurationFile = sprintf('%s%s%s', self::CONFIGURATION_FILES_FOLDER, $this->getConfigurationFileName(), self::CONFIGURATION_FILE_EXTENSION);
        if (!file_exists($configurationFile)) {
            throw new AuthorizationFileNotFound;
        }

        $this->configurationFile = $configurationFile;
    }

    private function getConfigurationFileName()
    {
        return Strings::lower(preg_replace('/\:/', '/', $this->name));
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }
}
