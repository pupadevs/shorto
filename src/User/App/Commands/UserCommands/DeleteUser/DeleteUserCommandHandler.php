<?php 

declare(strict_types=1);

namespace Source\User\App\Commands\UserCommands\DeleteUser;

use Source\User\Domain\Events\User\DeleteUserEvent\DeleteUserLogEvent;
use Source\User\Domain\Events\User\DeleteUserEvent\DeleteUserReadEvent;
use Source\User\Domain\Interfaces\UserRepositoryContracts\UserRepositoryInterface;

class DeleteUserCommandHandler
{
    /**
     * @var UserRepositoryInterface $userRepositoryInterface
     */ 
    private UserRepositoryInterface $userRepositoryInterface;

    /**
     * CommandHandler constructor.
     * @param UserRepositoryInterface $userRepositoryInterface
     */

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * Method to execute command
     * @param DeleteUserCommand $command
     * @return void
     */
    
    public function execute(DeleteUserCommand $command): void
    {
        $this->userRepositoryInterface->deleteUser($command->getUser());
        event(new DeleteUserReadEvent($command->getUser()));
        event(new DeleteUserLogEvent($command->getUser()->getId()->toString()));
    }
}