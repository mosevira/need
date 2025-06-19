<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{

    public function view(User $user, Invoice $invoice)
{
    return $user->isAdmin() || $invoice->branch_id === $user->branch_id;
}

public function accept(User $user, Invoice $invoice)
{
    return $invoice->status === Invoice::STATUS_IN_STORE
        && $user->branch_id === $invoice->branch_id;
}

public function close(User $user, Invoice $invoice)
{
    return in_array($invoice->status, [Invoice::STATUS_ACCEPTED, Invoice::STATUS_DISCREPANCY])
        && $user->isStorekeeper();
}

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        //
    }
}
