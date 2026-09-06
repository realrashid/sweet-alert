<?php

namespace RealRashid\SweetAlert\Concerns;

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;

/**
 * SweetAlertTrait - Livewire v4 integration trait.
 *
 * Add this trait to any Livewire component to get convenient alert/toast/input
 * helpers that dispatch browser events instead of relying on session flash.
 * The sweet-alert.js client-side listener handles the 'sweetalert' browser event.
 *
 * Usage inside a Livewire component:
 *
 *   use RealRashid\SweetAlert\Concerns\SweetAlertTrait;
 *
 *   class MyComponent extends Component
 *   {
 *       use SweetAlertTrait;
 *
 *       public function save(): void
 *       {
 *           // ...
 *           $this->sweetAlert()->success('Saved!')->dispatchToSelf();
 *       }
 *   }
 */
trait SweetAlertTrait
{
    /*
     * These return explicitly composed builders. A Livewire alert is delivered
     * as a browser event, so it must not also flash itself to the session —
     * that would show it a second time on the next full page load.
     */

    /**
     * Resolve a fresh AlertBuilder ready for fluent configuration.
     */
    public function sweetAlert(): AlertBuilder
    {
        return AlertBuilder::make();
    }

    /**
     * Resolve a fresh ToastBuilder ready for fluent configuration.
     */
    public function sweetToast(): ToastBuilder
    {
        return ToastBuilder::make();
    }

    /**
     * Resolve a fresh InputBuilder ready for fluent configuration.
     */
    public function sweetInput(): InputBuilder
    {
        return InputBuilder::make();
    }

    /**
     * Dispatch a pre-built AlertBuilder as a Livewire browser event.
     * The sweet-alert.js Livewire.on('sweetalert') handler picks it up.
     */
    public function dispatchAlert(AlertBuilder $builder): void
    {
        $this->dispatch('sweetalert', config: $builder->toArray());
    }

    /**
     * Dispatch a pre-built ToastBuilder as a Livewire browser event.
     */
    public function dispatchToast(ToastBuilder $builder): void
    {
        $this->dispatch('sweetalert', config: $builder->toArray());
    }

    /**
     * Dispatch a pre-built InputBuilder as a Livewire browser event.
     */
    public function dispatchInput(InputBuilder $builder): void
    {
        $this->dispatch('sweetalert', config: $builder->toArray());
    }
}
