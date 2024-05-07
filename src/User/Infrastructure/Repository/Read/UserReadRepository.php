<?php 

declare(strict_types=1);

namespace Source\User\Infrastructure\Repository\Read;

use Illuminate\Support\Facades\DB;
use Source\User\App\Events\UserCreatedReadEvent;
use Source\User\Domain\Entity\User;
use Source\User\Domain\Events\UserUpdatedReadEvent;
use Source\User\Domain\Interfaces\UserReadRepositoryInterface;
use Source\User\Domain\ValueObjects\Email;
use Source\User\Domain\ValueObjects\UserID;
use Source\User\Infrastructure\Repository\Exception\EmailExistsException;
use Source\User\Infrastructure\Repository\Exception\UserNotFoundException;

class UserReadRepository implements UserReadRepositoryInterface{

    /**
     * save a new user in the database Read
     * @param UserCreatedReadEvent $event
     * 
     */
    public function insertUserReadDB(UserCreatedReadEvent $event){

        DB::connection('mysql_read')->table('users')->insert([
            'id' => $event->getUser()->getId()->toString(),
            'name' => $event->getUser()->getName()->toString(),
            'email' => $event->getUser()->getEmail()->ToString(),
            'password' => $event->getUser()->getPassword()->ToString(),
            'created_at' => date('Y-m-d H:i:s'),

        ]);

    }
    /**
     * save a new user in the database Read 
     * @param UserUpdatedReadEvent $user
     * 
     */

    public function save(UserUpdatedReadEvent $user): void
    {
        $data= [
            'name' => $user->getUser()->getName()->toString(),
            'email' => $user->getUser()->getEmail()->toString(),
        ];
        DB::connection('mysql_read')->table('users')->where('id', $user->getUser()->getId()->toString())->update($data);
    }

    /**
     * get user by id from database read
     * @param UserID $userID
     * @return User
     * @throws UserNotFoundException
     */
 
    public function getUserByID(UserID $userID): User
    { 
      $user = DB::connection('mysql_read')->table('users')->where('id', $userID)->first();
    
        if (! $user) {
            throw new UserNotFoundException();
        }

        return User::fromArray(get_object_vars($user));

    }
    /**
     * get user by email from database read
     * @param Email $email
     * @throws UserNotFoundException
     * @return User
     */

    public function getUserByEmail(Email $email): User
    {
        
      $user = DB::connection('mysql_read')->table('users')->where('email', $email)->first();
    
        if (! $user) {
            throw new UserNotFoundException();
        }

        return User::fromArray(get_object_vars($user));
    }
    /**
     * check if email exists in database read
     * @param Email $email
     * @return bool
     */

    public function emailExists(Email $email): bool
    {

        $user =  DB::connection('mysql_read')->table('users')->where('email', $email->toString())->first();

        if (!$user) {
         return false;
        }

       throw new EmailExistsException();
    }
}