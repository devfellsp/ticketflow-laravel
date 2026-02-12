<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function list(array $filters = []);
    
    public function find(int $id): ?Ticket;
    
    public function create(array $data): Ticket;
    
    public function update(int $id, array $data): Ticket;
    
    public function delete(int $id): bool;
}                   