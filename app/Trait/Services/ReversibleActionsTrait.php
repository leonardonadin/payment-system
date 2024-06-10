<?php

namespace App\Trait\Services;

use Illuminate\Support\Facades\DB;

trait ReversibleActionsTrait
{
    public function persistOnSuccess($script)
    {
        $this->startActions();

        try {
            $result = $script();
        } catch (\Exception $e) {
            $this->rollbackActions();
            throw $e;
        }

        if ($result) {
            $this->commitActions();
        } else {
            $this->rollbackActions();
        }

        return $result;
    }

    protected function startActions()
    {
        DB::beginTransaction();
    }

    protected function commitActions()
    {
        DB::commit();
    }

    protected function rollbackActions()
    {
        DB::rollBack();
    }
}
