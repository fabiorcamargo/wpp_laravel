<?php

namespace App\Jobs;

use App\Http\Controllers\WppConnectController;
use App\Http\Controllers\WppScheduleController;
use App\Models\WppSchedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WppScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sch;
    protected $now;
    protected $nowm;
    protected $day;
    protected $i;
    /**
     * Create a new job instance.
     */
    public function __construct($sch, $now, $nowm, $day)
    {
        $this->sch = $sch;
        $this->now = $now;
        $this->nowm = $nowm;
        $this->day = Carbon::parse($day);
        //$this->i = $this->i + 6;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Executar a ação se o item for encontrado
        $this->teste();

        $this->i = $this->i + 3;

        $schedule = WppSchedule::where('time', '>=', $this->now)
            ->where('time', '<', $this->nowm)
            ->where('id', '>',  $this->sch->id)
            ->where('repeat', '>=',  1)
            ->first();


        if ($schedule) { //Se existir próximo usuário prosseguir
            dispatch(new WppScheduleJob($schedule, $this->now, $this->nowm, $this->day))->delay(now()->addSeconds($this->i));
        }
    }

    private function teste()
    {
        // Lógica para executar a ação no item
        // ...

        $dateToCompare = Carbon::parse($this->sch->date);

        $wpp = $this->sch->wpp()->first();

        //dd($agora);

        $wppc = new WppConnectController;

        // Verificar se é um agendamento diário
        if ($this->sch->period == '1') {
            // Agendamento diário
            // Faça o que precisa ser feito
            
            $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
            $this->sch->repeat = $this->sch->repeat - 1;
        }

        // Verificar se é um agendamento semanal
        if ($this->sch->period == '2' && $dateToCompare->format('N') == $this->day->format('N')) {
            // Agendamento semanal
            // Faça o que precisa ser feito
            
            $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
            $this->sch->repeat = $this->sch->repeat - 1;

        }


        // Verificar se é um agendamento de semanas alternadas
        if ($this->sch->period == '3') {

            if (!$dateToCompare->isSameDay($this->day)) {
                // Calcular a diferença em semanas entre a data de início e agora
                $semanasDiferenca = $dateToCompare->diffInWeeks($this->day);


                // Verificar se a diferença em semanas é par ou ímpar
                if (
                    $semanasDiferenca % 2 == 0
                    && $dateToCompare->dayOfWeek == $this->day->dayOfWeek
                    
                ) {
                    // Agendamento de semanas alternadas
                    // Faça o que precisa ser feito
                    
                    $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
                    $this->sch->repeat = $this->sch->repeat - 1;
                }
            } else {

                $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
                $this->sch->repeat = $this->sch->repeat - 1;
            }
        }

        // Verificar se é um agendamento mensal com o mesmo dia da semana
        if ($this->sch->period == '4') {

            if (!$dateToCompare->isSameDay($this->day)) {
                // Calcular a diferença em meses entre a data de início e agora
                $mesesDiferenca = $dateToCompare->diffInMonths($this->day);

                // Verificar se a diferença em meses é maior que 0 (ou seja, já passou pelo menos um mês)
                // e se o dia da semana da data de início é o mesmo que o dia da semana atual
                if ($mesesDiferenca > 0 && $dateToCompare->dayOfWeek == $this->day->dayOfWeek) {
                    // Código a ser executado se a condição for verdadeira
                    // Significa que passou pelo menos um mês e é o mesmo dia da semana

                    $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
                    $this->sch->repeat = $this->sch->repeat - 1;
                }
            } else {

                $wppc->SendMessage($wpp->session, $this->sch->group()->first()->group_id, $this->sch->body, true);
                $this->sch->repeat = $this->sch->repeat - 1;
            }
        }
        
        $this->sch->save();
    }
}
