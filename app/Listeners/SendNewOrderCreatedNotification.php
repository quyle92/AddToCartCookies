<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderCreatedMail;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class SendNewOrderCreatedNotification implements ShouldQueue
{   
    public $delay = 2;
    // public $queue = 'listeners';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {   
        $admin = User::where('name', 'admin')->first();
        Mail::to( $admin->email )->send(new OrderCreatedMail($event->order));
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return bool
     */
    // public function shouldQueue(OrderCreated $event)
    // {   
    //     // dd($event->order->grand_total);
    //     return $event->order->subtotal >= 100;
    // }
}
