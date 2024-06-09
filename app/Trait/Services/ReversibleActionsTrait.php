<?php

namespace App\Trait\Services;

use Illuminate\Support\Facades\DB;

trait ReversibleActionsTrait
{
    public function persistOnSuccess($script)
    {
        $this->startTransaction();

        try {
            $result = $script();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }

        if ($result) {
            $this->commitTransaction();
        } else {
            $this->rollbackTransaction();
        }

        return $result;
    }

    protected function startTransaction()
    {
        DB::beginTransaction();
    }

    protected function commitTransaction()
    {
        DB::commit();
    }

    protected function rollbackTransaction()
    {
        DB::rollBack();
    }
}
