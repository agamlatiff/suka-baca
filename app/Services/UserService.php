<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
  public function __construct(
    protected UserRepositoryInterface $userRepository
  ) {}

  public function getAllUsers(): Collection
  {
    return $this->userRepository->all();
  }

  public function getMembers(): Collection
  {
    return $this->userRepository->getMembers();
  }

  public function getAdmins(): Collection
  {
    return $this->userRepository->getAdmins();
  }

  public function getUserById(int $id): ?object
  {
    return $this->userRepository->find($id);
  }

  public function getUserByEmail(string $email): ?object
  {
    return $this->userRepository->findByEmail($email);
  }

  public function getTopBorrowers(int $limit = 10, ?string $startDate = null, ?string $endDate = null): Collection
  {
    return $this->userRepository->getTopBorrowers($limit, $startDate, $endDate);
  }

  public function createUser(array $data): object
  {
    if (isset($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    }

    return $this->userRepository->create($data);
  }

  public function updateUser(int $id, array $data): bool
  {
    if (isset($data['password']) && !empty($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    return $this->userRepository->update($id, $data);
  }

  public function deleteUser(int $id): bool
  {
    return $this->userRepository->delete($id);
  }

  public function suspendUser(int $userId): bool
  {
    return $this->userRepository->suspend($userId);
  }

  public function activateUser(int $userId): bool
  {
    return $this->userRepository->activate($userId);
  }

  public function isUserActive(int $userId): bool
  {
    $user = $this->userRepository->find($userId);
    return $user && $user->status === 'active';
  }
}
