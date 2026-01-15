<?php

namespace App\Observers;

use App\Mail\WorkerInvoiceUploaded;
use App\Models\Worker;
use Illuminate\Support\Facades\Mail;

class WorkerObserver
{
    /**
     * Handle the Worker "created" event.
     */
    public function created(Worker $worker): void
    {
        // Send email notification if invoice is uploaded during creation
        if ($worker->invoice_path) {
            Mail::to('hello@curohelp.com')->send(new WorkerInvoiceUploaded($worker));
        }
    }

    /**
     * Handle the Worker "updated" event.
     */
    public function updated(Worker $worker): void
    {
        // Send email notification if invoice was just uploaded or changed
        if ($worker->isDirty('invoice_path') && $worker->invoice_path) {
            Mail::to('hello@curohelp.com')->send(new WorkerInvoiceUploaded($worker));
        }
    }

    /**
     * Handle the Worker "deleted" event.
     */
    public function deleted(Worker $worker): void
    {
        //
    }

    /**
     * Handle the Worker "restored" event.
     */
    public function restored(Worker $worker): void
    {
        //
    }

    /**
     * Handle the Worker "force deleted" event.
     */
    public function forceDeleted(Worker $worker): void
    {
        //
    }
}
