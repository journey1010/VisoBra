<?php

use Illuminate\Support\Facades\Schedule;

use App\Console\Commands\CleanLogs;
use App\Console\Commands\UpdateContrataciones;
use App\Console\Commands\UpdateFotos;
use App\Console\Commands\UpdateObra;
use App\Console\Commands\SearchNewObras;

Schedule::command(SearchNewObras::class)
          ->weekly()
          ->timezone('America/Lima');
Schedule::command(UpdateContrataciones::class)
          ->weeklyOn(1, '01:00')
          ->timezone('America/Lima');
Schedule::command(UpdateFotos::class)
          ->weeklyOn(1, '01:00')
          ->timezone('America/Lima');
Schedule::command(UpdateObra::class)
          ->weeklyOn(1, '01:00')
          ->timezone('America/Lima');
Schedule::command(CleanLogs::class)
          ->weeklyOn(3, '01:00')
          ->timezone('America/Lima');