<?php
declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User\User;
use App\ReadModel\NotFoundException;
use App\ReadModel\User\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserFetcher
{
    /**
     * @var Connection;
     */
    private $connection;


    /**
     * @var EntityManagerInterface
     */
    private $repository;

    /**
     * @param Connection $connection
     * @param EntityManagerInterface $em
     */
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @param string $email
     * @return AuthView|null
     */
    public function findForAuthByEmail(string $email): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'TRIM(CONCAT(name_first, \' \', name_last)) as name',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function get(string $id): User
    {
        if (!$user = $this->repository->find($id)) {
            throw new NotFoundException('Пользователь не найден.');
        }

        /** @var User $user*/
        return $user;
    }

    /**
     * Получение профиля пользователя по id
     *
     * @param string $id
     *
     * @return array
     */
    public function findForProfileById(string $id): array
    {
        $user = $this->get($id);

        $name  = $user->getName();

        return [
            'id' => $user->getId()->getValue(),
            'email' => $user->getEmail()->getValue(),
            'name' => [
                'first' => $name->getFirst(),
                'last' => $name->getLast(),
                'full' => $name->getFull(),
            ],
            'status' => $user->getStatus()->getValue(),
            'role' => $user->getRole()->getName()
        ];
    }
}
