<?php

namespace App\Http\Controllers;

use App\Models\WppConnect;
use App\Models\WppSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WppScheduleController extends Controller
{

    protected $sch;
    protected $now;
    protected $nowm;
    protected $day;
    protected $i;

    public function inicio()
    {
        $this->now = now()->format('H:i:s');
        $this->nowm = now()->addMinute()->format('H:i:s');
        $this->day = now();

        $this->i = $this->i + 6;

        

        $this->sch = WppSchedule::find(2);
        $dateToCompare = Carbon::parse($this->sch->date);
        
        //dd($dateToCompare->format('N') == $this->day->format('N'));

        $firstJob = WppSchedule::where('time', '>=', $this->now)
            ->where('time', '<', $this->nowm)
            ->first();

        if ($firstJob) {
            $this->sch = $firstJob;
            $this->test();
        }
    }
    
    private function test()
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

            dd('day');
            
            $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
            $this->sch->repeat = $this->sch->repeat - 1;
        }

        // Verificar se é um agendamento semanal
        dd($this->sch->period == '2' , $dateToCompare->format('N') == $this->day->format('N'));
        if ($this->sch->period == '2' && $dateToCompare->format('N') == $this->day->format('N')) {
            // Agendamento semanal
            // Faça o que precisa ser feito


            dd('semana');
            
            $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
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


                    dd('semana alternada');
                    
                    $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
                    $this->sch->repeat = $this->sch->repeat - 1;
                }
            } else {


                
                $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
                $this->sch->repeat = $this->sch->repeat - 1;
            }
        }




        // Verificar se é um agendamento mensal com o mesmo day da semana
        if ($this->sch->period == '4') {

            if (!$dateToCompare->isSameDay($this->day)) {
                // Calcular a diferença em meses entre a data de início e agora
                $mesesDiferenca = $dateToCompare->diffInMonths($this->day);

                // Verificar se a diferença em meses é maior que 0 (ou seja, já passou pelo menos um mês)
                // e se o day da semana da data de início é o mesmo que o day da semana atual
                if ($mesesDiferenca > 0 && $dateToCompare->dayOfWeek == $this->day->dayOfWeek) {
                    // Código a ser executado se a condição for verdadeira
                    // Significa que passou pelo menos um mês e é o mesmo day da semana



                    dd('mensal');
                    
                    $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
                    $this->sch->repeat = $this->sch->repeat - 1;
                }
            } else {



                
                $wppc->SendMessage($wpp->session, $this->sch->group_id, $this->sch->body, true);
                $this->sch->repeat = $this->sch->repeat - 1;
            }
        }
    }
}
