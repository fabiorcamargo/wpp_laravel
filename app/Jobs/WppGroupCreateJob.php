<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WppGroupCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group;
    protected $wpp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $wpp)
    {
        $this->group = $group;
        $this->wpp = $wpp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->group->creation)) {
            $create = strlen($this->group->creation) > 10 ? date("Y-m-d H:i:s", $this->group->creation / 1000) : date("Y-m-d H:i:s", $this->group->creation);
        } else {
            $create = '';
        }

        if ($this->group->id) {
            if (!$this->wpp->Groups()->where('group_id', $this->group->id)->where('wpp_connect_id', $this->wpp->id)->exists()) {
                $this->wpp->Groups()->create([
                    'group_id' => $this->group->id,
                    'name' => isset($this->group->subject) ? $this->group->subject : 'Sem Nome',
                    'creation' =>  $create
                ]);
            }
        }
    }
}
